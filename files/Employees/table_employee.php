<div class="row">
    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Registered Employees</h4>
            </div>
            <?php
            // echo $_SERVER['DOCUMENT_ROOT'];
            ?>
            
<?php

// function generateUniqueCode() {
//     $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
//     $code = '';
    
//     for ($i = 0; $i < 5; $i++) {
//         $code .= $characters[random_int(0, strlen($characters) - 1)];
//     }
    
//     return $code;
// }

// function generateGuaranteedUniqueCode(PDO $conn) {
//     do {
//         $code = generateUniqueCode();
//         $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_info WHERE staff_id LIKE ?");
//         $stmt->execute(['%' . $code . '%']);
//         $exists = $stmt->fetchColumn();
//     } while ($exists > 0);
    
//     return $code;
// }

// $finalCode = generateGuaranteedUniqueCode($conn);
// echo "Generated Unique Code: " . $finalCode;

?>


            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="employees_table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Staff ID</th>
                                <th scope="col">Names</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <!--<th scope="col">Supervisor</th>-->
                                <th scope="col"><i class="fas fa-eye"></i></th>
                                <?php
                                if ($role_id != 9) {
                                    echo '
                                    <th scope="col"><i class="fas fa-pen"></i></th>
                                    <th scope="col"><i class="fa fa-refresh"></i></th>
                                    <th scope="col">Status</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query1 = $conn->prepare("SELECT tbl_staff_info.*, staff_post.*,
                            tbl_users.status AS stst, tbl_users.id as usesdID FROM tbl_staff_info
                            LEFT JOIN staff_post ON staff_post.staff_post_id = tbl_staff_info.supervisor
                            LEFT JOIN tbl_users ON tbl_users.Identification = tbl_staff_info.staff_id
                            ORDER BY tbl_staff_info.family_name");
                            $query1->execute();
                            if ($rows = $query1->rowCount() > 0) {
                                $a = 1;
                                while ($row = $query1->fetch()) {
                            ?>
                                    <tr>
                                        <td><?php echo $a++ ?></td>
                                        <td><?php echo $row['staff_id']; ?></td>
                                        <td><?php echo $row['family_name']; ?> <?php echo $row['first_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <!--<td>-->
                                            <?php 
                                            // echo $row['staff_post_full_name'];
                                            ?>
                                            <!--</td>-->
                                        <td>
                                            <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary view-employee"><span class="spinner80"></span><i class="fas fa-eye"></i></button>
                                        </td>
                                        
                                            <?php if ($role_id != 9) { ?>
                                            <td>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" 
                                                        data-emp-id="<?php echo $row['id']; ?>" 
                                                        data-first-name="<?php echo $row['first_name']; ?>" 
                                                        data-family-name="<?php echo $row['family_name']; ?>" 
                                                        data-post="<?php echo $row['Post']; ?>"
                                                        class="btn btn-sm btn-primary edit-employee">
                                                    <span class="spinner81"></span><i class="fas fa-pen"></i>
                                                </button>
                                                
                                                </td>
                                          <td>
                                              <button class="btn btn-success btn-sm reset" data-id="<?php echo $row['usesdID']; ?>">
                                            <span id="spinner33_<?php echo $row['usesdID']; ?>"></span>&nbsp;<i class="fa fa-refresh"></i></button>
                                          </td>
                                          <td>
                                              <label class="custom-switch btn btn-light btn-sm">
                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row['usesdID']; ?>" <?php echo $row['stst']==1?'checked':''; ?>>
                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row['usesdID']; ?>"></span>&nbsp;
                                            </label>
                                          </td>
                                            <?php } ?>
                                          
                                        
                                    </tr>
                            <?php }
                            } else {
                                // Handle no records case
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fs-5 fw-semibold" id="editEmployeeModalLabel">Update Employee Information</h5>
        <button type="button" class="btn-close btn-close-white btn btn-secondary text-white" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-body p-4">
        <!--<form id="edit_employee_card" method="POST" class="needs-validation" novalidate>-->
            <form id="edit_employee_card" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          <input type="hidden" name="emp_id" id="emp_id">
          <input type="hidden" name="action" value="edit_employee_card">
          
          <!-- Single row container for all cards -->
          <div class="row g-4">
            <!-- Personal Information -->
            
            
            <div class="col-md-6">
              <div class="card border-0 shadow-sm">
                  
                  <div class="mb-3 text-center">
    <div class="position-relative d-inline-block">
        <img src="/files/Employees/images/default_avatar.jpg" class="img-thumbnail rounded-circle mb-2" id="staffImagePreview" style="width: 120px; height: 120px; object-fit: cover;">
        <label class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle shadow-sm" style="width: 32px; height: 32px;">
            <i class="fas fa-camera"></i>
            <input type="file" class="d-none" id="staff_image" name="staff_image" accept="image/*">
        </label>
    </div>
    <div class="small text-muted">Click camera icon to upload photo</div>
</div>
<!--111111111111111111111-->


                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Personal Information</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="staff_id" class="form-label">Staff ID</label>
                    <input type="text" class="form-control form-control-sm" id="staff_id" name="staff_id" readonly>
                  </div>
                  <div class="mb-3">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="first_name" name="first_name">
                    <div class="invalid-feedback">Please provide first name</div>
                  </div>
                  <div class="mb-3">
                    <label for="family_name" class="form-label">Family Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="family_name" name="family_name">
                    <div class="invalid-feedback">Please provide family name</div>
                  </div>
                  <div class="mb-3">
                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                    <select class="form-control new select2" style="width:100%" id="gender1" name="gender">
                      <option value="M">Male</option>
                      <option value="F">Female</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" id="dob" name="dob">
                  </div>
                  <div class="mb-3">
                    <label for="marital_status" class="form-label">Marital Status</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="marital_status" name="marital_status">
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Divorced">Divorced</option>
                      <option value="Widowed">Widowed</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="nid" class="form-label">National ID</label>
                    <input type="text" class="form-control form-control-sm" id="nidId" name="nidId">
                  </div>
                  <div class="mb-3">
                    <label for="nid" class="form-label">Nassit Number</label>
                    <input type="text" class="form-control form-control-sm" id="Nassit_Number" name="Nassit_Number">
                  </div>
                  
                  <div class="mb-3">
                    <label for="nid" class="form-label">Qualifications</label>
                    <input type="text" class="form-control form-control-sm" id="editQualifications" name="editQualifications">
                  </div>
                  <div class="mb-3">
                    <label for="nid" class="form-label">Department / Unit</label>
                    <input type="text" class="form-control form-control-sm" id="deptuntedit" name="deptuntedit">
                  </div>
                  
                  
                </div>
              </div>
              
              <!-- Family Information -->
              <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Family Information</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="mother_name" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control form-control-sm" id="mother_name" name="mother_name">
                  </div>
                  <div class="mb-3">
                    <label for="father_name" class="form-label">Father's Name</label>
                    <input type="text" class="form-control form-control-sm" id="father_name" name="father_name">
                  </div>
                </div>
              </div>
              
              <!-- Financial Information -->
              <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Financial Information</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="bank" class="form-label">Bank Name</label>
                    <input type="text" class="form-control form-control-sm" id="bank" name="bank">
                  </div>
                  <div class="mb-3">
                    <label for="acc_no" class="form-label">Account Number</label>
                    <input type="text" class="form-control form-control-sm" id="acc_no" name="acc_no">
                  </div>
                  <!--<div class="mb-3">-->
                  <!--  <label for="rssb" class="form-label">RSSB Number</label>-->
                  <!--  <input type="text" class="form-control form-control-sm" id="rssbms" name="rssbms">-->
                  <!--</div>-->
                  <div class="mb-3">
                    <label for="gross_salary" class="form-label">Gross Salary</label>
                    <input type="number" class="form-control form-control-sm" id="gross_salary" name="gross_salary">
                  </div>
                  
                  <div class="mb-3">
                    <label for="gross_salary" class="form-label">Scale of Salary</label>
                    <input type="number" class="form-control form-control-sm" id="editScaleofSalary" name="editScaleofSalary">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-md-6">
              <!-- Contact Information -->
              <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Contact Information</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control form-control-sm" id="phoneeee" name="phoneeee">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control form-control-sm" id="emaillll" name="emaillll">
                  </div>
                  
                  <h6 class="mt-4 fw-semibold border-top pt-3">Address Information</h6>
                  <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="countryy" name="countryy">
                      <?php 
                      $sql = "SELECT * FROM tbl_country";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['cntr_id']; ?>"><?php echo $row['cntr_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="mb-3" style="display:none" id="filProvince">
                    <label for="province" class="form-label">Province</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="province" name="province">
                        <option>---</option>
                      <?php 
                      $sql = "SELECT * FROM provinces";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['provincecode']; ?>"><?php echo $row['provincename']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="mb-3" style="display:none" id="FMydistrict">
                    <label for="district" class="form-label">District</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="Mydistrict" name="Mydistrict">
                        
                    </select>
                    
                  </div>
                  <div class="mb-3" style="display:none" id="FMysector">
                    <label for="sector" class="form-label">Sector</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="Mysector" name="Mysector">
                    </select>
                  </div>
                  
                  <div class="mb-3" style="display:none" id="FMycell">
                    <label for="cell" class="form-label">Cell</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="Mycell" name="Mycell"></select>
                  </div>
                  <div class="mb-3" style="display:none" id="FMyvillage">
                    <label for="village" class="form-label">Village</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="Myvillage" name="Myvillage"></select>
                  </div>
                </div>
              </div>
              

              <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Employment Information</h6>
                </div>
                <div class="card-body">
                    
                    <div class="mb-3">
                        <label for="sCategory" class="form-label">Staff Category <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select2" style="width: 100%" id="StaffCategory" name="StaffCategory">
                          <?php 
                          $sql = "SELECT * FROM tbl_staff_type";
                          $result = $conn->query($sql);
                          ?>
                         
                          <?php while ($row = $result->fetch()) { ?>
                            <option value="<?php echo $row['staff_type_id']; ?>"><?php echo $row['staff_type_full_name']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="School" class="form-label">School <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select2" style="width: 100%" id="Schoolfld" name="Schoolfld">
                          <?php 
                          $sql = "SELECT tbl_faculty.* FROM tbl_faculty
                            JOIN tbl_program_type ON tbl_program_type.prg_type_id = tbl_faculty. prg_type";
                          $result = $conn->query($sql);
                          ?>
                         
                          <?php while ($row = $result->fetch()) { ?>
                            <option value="<?php echo $row['fac_id']; ?>"><?php echo $row['fac_full_name']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                  
                    
                  
                  <div class="mb-3">
                    <label for="Department" class="form-label">Department</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="Departmentup" name="Departmentup">
                      <?php 
                      $sql = "SELECT * FROM tbl_department";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['dept_id']; ?>"><?php echo $row['dept_full_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label for="post" class="form-label">Position </label>
                        <select class="form-select form-select-sm select2" style="width: 100%" id="post" name="post[]" multiple>
                          <?php 
                          $sql = "SELECT * FROM staff_post ORDER BY staff_post_full_name ASC";
                          $result = $conn->query($sql);
                          while ($row = $result->fetch()) {
                            $selected = in_array($row['staff_post_id'], $selectedPositions) ? 'selected' : '';
                            echo '<option value="' . $row['staff_post_id'] . '" ' . $selected . '>' . htmlspecialchars($row['staff_post_full_name']) . '</option>';
                          }
                          ?>
                        </select>
                  </div>
                  
                <div class="mb-3" id="probation_form" style="display:block">
                    <label for="probation_range">
                        Probation Period (Years): 
                        <code><b><span id="probation_star"></span></b></code>
                    </label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="rc2probation_range" name="probation_years">
                          <option value="1"> 1 Year</option>
                          <option value="2"> 2 Years</option>
                          <option value="3"> 3 Years</option>
                          <option value="4"> 4 Years</option>
                          <option value="5"> 5 Years</option>
                        </select>
                </div>
                <!--<div class="mb-3" id="probation_form" style="display:block">-->
                <!--    <label for="probation_range">-->
                <!--        Probation Period (Years): -->
                <!--        <code><b><span id="probation_star"></span></b></code>-->
                <!--    </label>-->
                <!--    <input type="range" class="form-control-range" id="rc2probation_range" name="probation_years" min="1" max="5" value="1" step="1" required>-->
                <!--    <small>Selected: <span id="r2probation_display">1</span> Year(s)</small>-->
                <!--    <span id="spinner32"></span>-->
                <!--</div>-->


                  <div class="mb-3">
                    <label for="campus" class="form-label">Campus</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="campus" name="campus">
                      <?php 
                      $sql = "SELECT * FROM tbl_campus";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['camp_id']; ?>"><?php echo $row['camp_full_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  
                  <!--<div class="mb-3">-->
                  <!--  <label for="campus" class="form-label">Supervisor</label>-->
                  <!--  <select class="form-select form-select-sm select2" style="width: 100%" id="fSupervisor_id" name="fSupervisor_id">-->
                      <?php 
                    //   $sql = "SELECT * FROM staff_post";
                    //   $result = $conn->query($sql);
                    //   while ($row = $result->fetch()) { 
                      ?>
                        <!--<option value="<?php echo $row['staff_post_id']; ?>"><?php echo $row['staff_post_full_name']; ?></option>-->
                      <?php 
                    //   } 
                      ?>
                  <!--  </select>-->
                  <!--</div>-->
                  
                  <div class="mb-3">
                    <label for="contract" class="form-label">Contract Type</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="contract" name="contract">
                      <?php 
                      $sql = "SELECT * FROM tbl_contract_type";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['contr_type_id']; ?>"><?php echo $row['contr_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  
                  <div class="mb-3">
                    <label for="contract" class="form-label">Employee role in system</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="rolesid" name="rolesid">
                      <?php 
                      $sql = "SELECT * FROM tbl_user_roles";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['role_id']; ?>"><?php echo $row['role']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  
                  
                  
                  
                </div>
              </div>
              
              <!-- Nationality -->
              <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                  <h6 class="mb-0 fw-semibold">Nationality</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select form-select-sm select2" style="width: 100%" id="nationalityy" name="nationality">
                      <?php 
                      $sql = "SELECT * FROM tbl_nationality";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch()) { ?>
                        <option value="<?php echo $row['nat_id']; ?>"><?php echo $row['cntr_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="modal-footer border-0 pt-4 px-0 pb-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
include 'scrpt.php';
?>
<script>


document.getElementById('staff_image').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('staffImagePreview').src = reader.result;
    }
    reader.readAsDataURL(e.target.files[0]);
});

    document.addEventListener('DOMContentLoaded', function() {
    var editButtons = document.querySelectorAll('.edit-employee');
    
    
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var empId = button.getAttribute('data-emp-id');
            
            $.ajax({
                type: "POST",
                url: "/files/Employees/controller.php",
                data: { 
                    action: "get_employee_data",
                    emp_id: empId 
                },
                dataType: "JSON",
                success: function(data) {
                    console.log("Received data:", data);
                    if(data.status == 200) {
                        // Populate personal info
                        var positionId = data.post;
                        console.log("Setting position to...:", positionId);
                        
                        console.log("prob: " + data.employee.probation_years);
    if (data.employee && data.employee.probation_years) {
        const probationYears = data.employee.probation_years;
        $('#rc2probation_range').val(probationYears).trigger('input');
        // $('#probation_display').text(probationYears);
        $('#r2probation_display').text(probationYears);
    }

    $('#rc2probation_range').on('input', function() {
        $('#r2probation_display').text(this.value);
    });
    
                        $('#emp_id').val(empId);
                        $('#staff_id').val(data.employee.staff_id);
                        $('#first_name').val(data.employee.first_name);
                        $('#family_name').val(data.employee.family_name);
                        $('#gender1').val(data.employee.gender).trigger('change');
                        $('#dob').val(data.employee.dob);
                        $('#marital_status').val(data.employee.marital_status).trigger('change');
                        $('#nidId').val(data.employee.nid);
                        $('#Nassit_Number').val(data.employee.Nassit_Number);
                        
                        $('#editQualifications').val(data.employee.qualifications);
                        $('#deptuntedit').val(data.employee.deptunt);
                        
                        
                        // Contact info
                        $('#phoneeee').val(data.employee.phone);
                        $('#emaillll').val(data.employee.email);
                        $('#gross_salary').val(data.employee.gross_salary);
                        $('#editScaleofSalary').val(data.employee.ScaleofSalary);
                        
                        // Family info
                        $('#mother_name').val(data.employee.mother_name);
                        $('#father_name').val(data.employee.father_name);
                        
                        // Financial info
                        $('#bank').val(data.employee.bank);
                        $('#acc_no').val(data.employee.acc_no);
                        $('#rssbms').val(data.employee.rssb);
                        
                        // Address info
                        // if (data.employee.staff_image != '') {
                        //     $('#staffImagePreview').attr('src', '/files/Employees/images/' + data.employee.staff_image);
                        // } else {
                        //     $('#staffImagePreview').attr('src', '/files/Employees/images/files/Employees/images/default_avatar.jpg';
                        // }
                        
                        if (data.employee.staff_image && data.employee.staff_image !== '') {
                            $('#staffImagePreview').attr('src', '' + data.employee.staff_image);
                            // $('#staffImagePreview').attr('src', '/staff_docs/' + data.employee.staff_image);
                        } else {
                            $('#staffImagePreview').attr('src', '/files/Employees/images/default_avatar.jpg');
                        }



                        $('#countryy').val(data.employee.country).trigger('change');
                        $('#contract').val(data.employee.contract).trigger('change');
                        if(data.employee.country == "160") {
                            
                            $('#province').val(data.employee.province).trigger('change');
                            setTimeout(function() {
                                $('#Mydistrict').val(data.employee.district).trigger('change');
                                setTimeout(function() {
                                    $('#Mysector').val(data.employee.sector).trigger('change');
                                    $('#Mycell').val(data.employee.cell).trigger('change');
                                    $('#Myvillage').val(data.employee.village).trigger('change');
                                    
                                }, 300);
                            }, 300);
                        }
                        
                        // Employment info
                        
                        var positionId = data.post;
                        console.log("Setting position to:", positionId);
                        $('#post').val(positionId).trigger('change');
        
                        // $('#post').val(data.employee.Post).trigger('change');
                        $('#campus').val(data.employee.campus).trigger('change');
                        $('#StaffCategory').val(data.employee.staffCategory).trigger('change');
                        
                        $('#Departmentup').val(data.employee.departmentLD).trigger('change');
                        
                        console.log("hhhhhh " + data.employee.schoolld);
                        $('#Schoolfld').val(data.employee.school).trigger('change');
                        // $('#probation_range').val(data.employee.probation_years).trigger('change');
                        
                        
                       


                                            
                        // Nationality
                        $('#nationalityy').val(data.employee.nationality).trigger('change');
                        
                        // User role
                        if(data.user) {
                            $('#rolesid').val(data.user.role_id).trigger('change');
                        }
                        
                        // Show the modal
                        $('#editEmployeeModal').modal('show');
                    } else {
                        pop_wrong(data.message);
                    }
                },
                error: function() {
                    pop_wrong("Failed to load employee data");
                }
            });
        });
    });
    
    
    
    
});

</script>