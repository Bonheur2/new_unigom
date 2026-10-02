=>1

My Update Employee pop up model part is not showing up all data in the form to help me to update them easly.
the following is the structure of tables that holds all data, and that is where you you will update accondly.

and when user selected country where tbl_country.cntr_id == 160 that when you will allow him to update also:
provinces, districts, sectors, cells, villages other wise he will not be able to update them.

Table to update: tbl_staff_info 
    (staff_id, family_name, first_name, gender, dob, marital_status, nationality(fk tbl_nationality.nat_id), nid, phone, 
     email, staff_image, country(fk tbl_country.cntr_id), province(fk provinces.provincecode),
     district(fk districts.districtcode), sector(fk sectors.sectorcode), cell(fk cells. codecell), village(fk villages.CodeVillage), mother_name, 
     father_name, bank, acc_no, rssb, campus(fk))		

Table to update: tbl_staff_salary 
            (staff_Id, gross_salary, starting_date)	

Table to update: tbl_staff_post 
            (staff_id, is_acadmic(fk tbl_staff_type.staff_type_id), acad_grad_id(fk tbl_acad_grade.acad_grad_id), 
            department(fk tbl_department.dept_id), post(fk staff_post.staff_post_id), basic_salary, rssb, contract(fk tbl_contract_type.contr_type_id), 
             campus(fk tbl_campus.camp_full_name), probation_period, join_date)	

Table to update: tbl_users 
            (Identification(staff_id), family_name, first_name, email, phone_no, password, role_id(fk tbl_user_roles.role_id))
            
Table will help to fetch data: tbl_nationality (nat_id, nationality)

Table will help to fetch data: tbl_country (cntr_id, cntr_name)

Table will help to fetch data: provinces (provincecode, provincename)
Table will help to fetch data: districts (districtcode, namedistrict, provincecode)
Table will help to fetch data: sectors (sectorcode, namesector, districtcode)
Table will help to fetch data: cells (codecell, nameCell, sectorcode)
Table will help to fetch data: villages (CodeVillage, VillageName, codecell)

Table will help to fetch data: tbl_campus (camp_id, camp_full_name)

Table will help to fetch data: tbl_staff_type(staff_type_id, staff_type_full_name)
Table will help to fetch data: tbl_acad_grade (acad_grad_id, acad_grad_full_name)
Table will help to fetch data: tbl_department (dept_id, dept_full_name)
Table will help to fetch data: staff_post (staff_post_id, staff_post_full_name)
Table will help to fetch data: tbl_contract_type (contr_type_id, contr_name)
Table will help to fetch data: tbl_user_roles (role_id, role)

the following is my html and java scrip part

<div class="row">
    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Registered Employees</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="employees_table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Staff ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query1 = $conn->prepare("SELECT * FROM tbl_staff_info ");
                            $query1->execute();
                            if ($rows = $query1->rowCount() > 0) {
                                $a = 1;
                                while ($row = $query1->fetch()) {
                            ?>
                                    <tr>
                                        <td><?php echo $a++ ?></td>
                                        <td><?php echo $row['staff_id']; ?></td>
                                        <td><?php echo $row['family_name']; ?></td>
                                        <td><?php echo $row['first_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td>
                                            <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary view-employee"><span class="spinner80"></span><i class="fas fa-eye"></i></button>
                                            <?php if ($role_id == 7 || $role_id == 9) { ?>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" 
                                                        data-emp-id="<?php echo $row['id']; ?>" 
                                                        data-first-name="<?php echo $row['first_name']; ?>" 
                                                        data-family-name="<?php echo $row['family_name']; ?>" 
                                                        data-post="<?php echo $row['Post']; ?>"
                                                        class="btn btn-sm btn-primary edit-employee">
                                                    <span class="spinner81"></span><i class="fas fa-pen"></i> Edit
                                                </button>
                                            <?php } ?>
                                            <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary delete-employee"><span class="spinner82"></span><i class="fas fa-trash"></i></button>
                                        </td>
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



=>2

<!-- Modal Structure -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmployeeModalLabel">Update Employee</h5>
        <button type="button" class="btn-close btn btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
      </div>
      <div class="modal-body">
        <form id="edit_employee_card" method="POST">
          <input type="hidden" name="emp_id" id="emp_id">
          <input type="hidden" name="action" value="edit_employee_card">
          
          <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
              <h5>Personal Information</h5>
              <div class="mb-3">
                <label for="staff_id" class="form-label">Staff ID</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" required>
              </div>
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
              </div>
              <div class="mb-3">
                <label for="family_name" class="form-label">Family Name</label>
                <input type="text" class="form-control" id="family_name" name="family_name" required>
              </div>
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
              </div>
              <div class="mb-3">
                <label for="marital_status" class="form-label">Marital Status</label>
                <select class="form-control" id="marital_status" name="marital_status">
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Divorced">Divorced</option>
                  <option value="Widowed">Widowed</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="nid" class="form-label">National ID</label>
                <input type="text" class="form-control" id="nid" name="nid">
              </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-6">
              <h5>Contact Information</h5>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              
              <h5 class="mt-4">Address Information</h5>
              <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-control" id="country" name="country">
                  <?php 
                  $sql = "SELECT * FROM tbl_country";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['cntr_id']; ?>"><?php echo $row['cntr_nationality']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="province" class="form-label">Province</label>
                <select class="form-control" id="province" name="province">
                  <?php 
                  $sql = "SELECT * FROM provinces";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['provincecode']; ?>"><?php echo $row['provincename']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="district" class="form-label">District</label>
                <input type="text" class="form-control" id="district" name="district">
              </div>
              <div class="mb-3">
                <label for="sector" class="form-label">Sector</label>
                <input type="text" class="form-control" id="sector" name="sector">
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Additional Information -->
            <div class="col-md-6">
              <h5>Family Information</h5>
              <div class="mb-3">
                <label for="mother_name" class="form-label">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name">
              </div>
              <div class="mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name">
              </div>
            </div>
            
            <div class="col-md-6">
              <h5>Employment Information</h5>
              <div class="mb-3">
                <label for="post" class="form-label">Position</label>
                <select class="form-control" id="post" name="post" required>
                  <?php 
                  $sql = "SELECT * FROM staff_post";
                  $result = $conn->query($sql);
                  ?>
                  <option value="" selected disabled>--Choose One--</option>
                  <?php while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['staff_post_id']; ?>"><?php echo $row['staff_post_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="campus" class="form-label">Campus</label>
                <select class="form-control" id="campus" name="campus">
                  <?php 
                  $sql = "SELECT * FROM tbl_campus";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['camp_id']; ?>"><?php echo $row['camp_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="card_no" class="form-label">Card Number</label>
                <input type="text" class="form-control" id="card_no" name="card_no">
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Financial Information -->
            <div class="col-md-6">
              <h5>Financial Information</h5>
              <div class="mb-3">
                <label for="bank" class="form-label">Bank Name</label>
                <input type="text" class="form-control" id="bank" name="bank">
              </div>
              <div class="mb-3">
                <label for="acc_no" class="form-label">Account Number</label>
                <input type="text" class="form-control" id="acc_no" name="acc_no">
              </div>
              <div class="mb-3">
                <label for="rssb" class="form-label">RSSB Number</label>
                <input type="text" class="form-control" id="rssb" name="rssb">
              </div>
            </div>
            
            <!-- Nationality -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nationality" class="form-label">Nationality</label>
                <select class="form-control" id="nationality" name="nationality">
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="edit_employee_card">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var editButtons = document.querySelectorAll('.edit-employee');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the employee ID
            var empId = button.getAttribute('data-emp-id');
            
            // Fetch all employee data via AJAX
            $.ajax({
                type: "POST",
                url: "/files/Employees/controller.php",
                data: { 
                    action: "get_employee_data",
                    emp_id: empId 
                },
                dataType: "JSON",
                success: function(data) {
                    if(data.status == 200) {
                        // Populate all form fields with the received data
                        for(var key in data.employee) {
                            if(document.getElementById(key)) {
                                document.getElementById(key).value = data.employee[key];
                            }
                        }
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

<script>
$("#edit_employee_card").submit(function(e) {
    e.preventDefault();
    $('.spinner81').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    var formData = new FormData(this);
    
    $.ajax({
        type: "POST",
        url: "/files/Employees/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: function(data) {
            $('.spinner81').fadeOut('fast');
            if (data.status == 200) {
                pop_up_success(data.message);
                $('#employees_table').load(location.href + " #employees_table");
                $('#editEmployeeModal').modal('hide');
            }
            if (data.status == 500) {
                pop_wrong(data.message);
            }
        },
        error: function() {
            $('.spinner81').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});

</script>

the following my php part
controller.php
else if ($action == "edit_employee_card") {
    $id = $_POST['emp_id'];
    
    // Prepare the update data
    $updateData = [
        'staff_id' => $_POST['staff_id'],
        'first_name' => $_POST['first_name'],
        'family_name' => $_POST['family_name'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'marital_status' => $_POST['marital_status'],
        'nationality' => $_POST['nationality'],
        'nid' => $_POST['nid'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'country' => $_POST['country'],
        'province' => $_POST['province'],
        'district' => $_POST['district'],
        'sector' => $_POST['sector'],
        'cell' => $_POST['cell'] ?? null,
        'village' => $_POST['village'] ?? null,
        'mother_name' => $_POST['mother_name'],
        'father_name' => $_POST['father_name'],
        'bank' => $_POST['bank'],
        'acc_no' => $_POST['acc_no'],
        'rssb' => $_POST['rssb'],
        'campus' => $_POST['campus'],
        'card_no' => $_POST['card_no'],
        'Post' => $_POST['post'],
        'id' => $id
    ];

    try {
        // Build the SQL query dynamically
        $sql = "UPDATE tbl_staff_info SET ";
        $params = [];
        foreach ($updateData as $field => $value) {
            if ($field != 'id') {
                $sql .= "$field = :$field, ";
                $params[":$field"] = $value;
            }
        }
        $sql = rtrim($sql, ", ") . " WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        $data = array("status" => "200", "message" => "Employee updated successfully!");
    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}
else if ($action == "get_employee_data") {
    $id = $_POST['emp_id'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_staff_info WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($employee = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data = array("status" => "200", "employee" => $employee);
        } else {
            $data = array("status" => "404", "message" => "Employee not found");
        }
    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}




=>2





give me full javascript and php functions in controller.php eg: get_districts(), get_sectors()

// Province to District
$('#province').change(function() {
    var provinceCode = $(this).val();
    if(provinceCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_districts",
                provincecode: provinceCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#district').empty();
                $.each(data, function(key, value) {
                    $('#district').append('<option value="'+value.districtcode+'">'+value.namedistrict+'</option>');
                });
            }
        });
    }
});

// District to Sector
$('#district').change(function() {
    var districtCode = $(this).val();
    if(districtCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_sectors",
                districtcode: districtCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#sector').empty();
                $.each(data, function(key, value) {
                    $('#sector').append('<option value="'+value.sectorcode+'">'+value.namesector+'</option>');
                });
            }
        });
    }
});

// Add similar handlers for sector→cell and cell→village

and give me full fuction to update

else if ($action == "edit_employee_card") {
    $id = $_POST['emp_id'];
    
    try {
        $conn->beginTransaction();
        
        // Update tbl_staff_info
        $updateStaff = [
            'staff_id' => $_POST['staff_id'],
            'first_name' => $_POST['first_name'],
            // ... all other staff_info fields
        ];
        // Build and execute staff_info update
        
        // Update tbl_staff_salary
        if(isset($_POST['gross_salary'])) {
            $updateSalary = [
                'staff_Id' => $_POST['staff_id'],
                'gross_salary' => $_POST['gross_salary']
            ];
            // Build and execute salary update
        }
        
        // Update tbl_staff_post
        $updatePost = [
            'staff_id' => $_POST['staff_id'],
            'post' => $_POST['post'],
            'contract' => $_POST['contract']
            // ... other post fields
        ];
        // Build and execute post update
        
        // Update tbl_users if needed
        if(isset($_POST['email'])) {
            $updateUser = [
                'Identification' => $_POST['staff_id'],
                'email' => $_POST['email'],
                'phone_no' => $_POST['phone']
            ];
            // Build and execute user update
        }
        
        $conn->commit();
        $data = array("status" => "200", "message" => "Employee updated successfully!");
    } catch (PDOException $e) {
        $conn->rollBack();
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}


=>3



I am not getting the following data in edit form:
Staff ID (tbl_staff_info.staff_id), Phone (tbl_staff_info.phone), Email (tbl_staff_info), National ID (tbl_staff_info),
RSSB Number (tbl_staff_info.rssb), Gross Salary (tbl_staff_post.basic_salary), Contract Type(tbl_staff_post.contract)

the following is table structures i am using 
Table to update: tbl_staff_info 
    (staff_id, family_name, first_name, gender, dob, marital_status, nationality(fk tbl_nationality.nat_id), nid, phone, 
     email, staff_image, country(fk tbl_country.cntr_id), province(fk provinces.provincecode),
     district(fk districts.districtcode), sector(fk sectors.sectorcode), cell(fk cells. codecell), village(fk villages.CodeVillage), mother_name, 
     father_name, bank, acc_no, rssb, campus(fk))		

Table to update: tbl_staff_salary 
            (staff_Id, gross_salary, starting_date)	

Table to update: tbl_staff_post 
            (staff_id, is_acadmic(fk tbl_staff_type.staff_type_id), acad_grad_id(fk tbl_acad_grade.acad_grad_id), 
            department(fk tbl_department.dept_id), post(fk staff_post.staff_post_id), basic_salary, rssb, contract(fk tbl_contract_type.contr_type_id), 
             campus(fk tbl_campus.camp_full_name), probation_period, join_date)	

Table to update: tbl_users 
            (Identification(staff_id), family_name, first_name, email, phone_no, password, role_id(fk tbl_user_roles.role_id))
            
Table will help to fetch data: tbl_nationality (nat_id, nationality)

Table will help to fetch data: tbl_country (cntr_id, cntr_name)

Table will help to fetch data: provinces (provincecode, provincename)
Table will help to fetch data: districts (districtcode, namedistrict, provincecode)
Table will help to fetch data: sectors (sectorcode, namesector, districtcode)
Table will help to fetch data: cells (codecell, nameCell, sectorcode)
Table will help to fetch data: villages (CodeVillage, VillageName, codecell)

Table will help to fetch data: tbl_campus (camp_id, camp_full_name)

Table will help to fetch data: tbl_staff_type(staff_type_id, staff_type_full_name)
Table will help to fetch data: tbl_acad_grade (acad_grad_id, acad_grad_full_name)
Table will help to fetch data: tbl_department (dept_id, dept_full_name)
Table will help to fetch data: staff_post (staff_post_id, staff_post_full_name)
Table will help to fetch data: tbl_contract_type (contr_type_id, contr_name)
Table will help to fetch data: tbl_user_roles (role_id, role)

the followig is my html and java script

<div class="row">
    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Registered Employees</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="employees_table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Staff ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query1 = $conn->prepare("SELECT * FROM tbl_staff_info ");
                            $query1->execute();
                            if ($rows = $query1->rowCount() > 0) {
                                $a = 1;
                                while ($row = $query1->fetch()) {
                            ?>
                                    <tr>
                                        <td><?php echo $a++ ?></td>
                                        <td><?php echo $row['staff_id']; ?></td>
                                        <td><?php echo $row['family_name']; ?></td>
                                        <td><?php echo $row['first_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td>
                                            <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary view-employee"><span class="spinner80"></span><i class="fas fa-eye"></i></button>
                                            <?php if ($role_id == 7 || $role_id == 9) { ?>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" 
                                                        data-emp-id="<?php echo $row['id']; ?>" 
                                                        data-first-name="<?php echo $row['first_name']; ?>" 
                                                        data-family-name="<?php echo $row['family_name']; ?>" 
                                                        data-post="<?php echo $row['Post']; ?>"
                                                        class="btn btn-sm btn-primary edit-employee">
                                                    <span class="spinner81"></span><i class="fas fa-pen"></i> Edit
                                                </button>
                                            <?php } ?>
                                            <button type="button" data-emp-id="<?php echo $row['id'] ?>" class="btn btn-sm btn-primary delete-employee"><span class="spinner82"></span><i class="fas fa-trash"></i></button>
                                        </td>
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
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmployeeModalLabel">Update Employee</h5>
        <button type="button" class="btn-close btn btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
      </div>
      <div class="modal-body">
        <form id="edit_employee_card" method="POST">
          <input type="hidden" name="emp_id" id="emp_id">
          <input type="hidden" name="action" value="edit_employee_card">
          
          <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
              <h5>Personal Information</h5>
              <div class="mb-3">
                <label for="staff_id" class="form-label">Staff ID</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" readonly>
              </div>
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
              </div>
              <div class="mb-3">
                <label for="family_name" class="form-label">Family Name</label>
                <input type="text" class="form-control" id="family_name" name="family_name" required>
              </div>
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
              </div>
              <div class="mb-3">
                <label for="marital_status" class="form-label">Marital Status</label>
                <select class="form-control" id="marital_status" name="marital_status">
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Divorced">Divorced</option>
                  <option value="Widowed">Widowed</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="nid" class="form-label">National ID</label>
                <input type="text" class="form-control" id="nid" name="nid">
              </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-6">
              <h5>Contact Information</h5>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              
              <h5 class="mt-4">Address Information</h5>
              <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-control" id="country" name="country">
                  <?php 
                  $sql = "SELECT * FROM tbl_country";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['cntr_id']; ?>"><?php echo $row['cntr_nationality']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="province" class="form-label">Province</label>
                <select class="form-control" id="province" name="province">
                  <?php 
                  $sql = "SELECT * FROM provinces";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['provincecode']; ?>"><?php echo $row['provincename']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="district" class="form-label">District</label>
                <input type="text" class="form-control" id="district" name="district">
              </div>
              <div class="mb-3">
                <label for="sector" class="form-label">Sector</label>
                <input type="text" class="form-control" id="sector" name="sector">
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Additional Information -->
            <div class="col-md-6">
              <h5>Family Information</h5>
              <div class="mb-3">
                <label for="mother_name" class="form-label">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name">
              </div>
              <div class="mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name">
              </div>
            </div>
            
            <div class="col-md-6">
              <h5>Employment Information</h5>
              <div class="mb-3">
                <label for="post" class="form-label">Position</label>
                <select class="form-control" id="post" name="post" required>
                  <?php 
                  $sql = "SELECT * FROM staff_post";
                  $result = $conn->query($sql);
                  ?>
                  <option value="" selected disabled>--Choose One--</option>
                  <?php while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['staff_post_id']; ?>"><?php echo $row['staff_post_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="campus" class="form-label">Campus</label>
                <select class="form-control" id="campus" name="campus">
                  <?php 
                  $sql = "SELECT * FROM tbl_campus";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['camp_id']; ?>"><?php echo $row['camp_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Financial Information -->
            <div class="col-md-6">
              <h5>Financial Information</h5>
              <div class="mb-3">
                <label for="bank" class="form-label">Bank Name</label>
                <input type="text" class="form-control" id="bank" name="bank">
              </div>
              <div class="mb-3">
                <label for="acc_no" class="form-label">Account Number</label>
                <input type="text" class="form-control" id="acc_no" name="acc_no">
              </div>
              <div class="mb-3">
                <label for="rssb" class="form-label">RSSB Number</label>
                <input type="text" class="form-control" id="rssb" name="rssb">
              </div>
            </div>
            
            <!-- Nationality -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nationality" class="form-label">Nationality</label>
                <select class="form-control" id="nationality" name="nationality">
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
          
          <!-- Add these inside your form where appropriate -->
<div class="mb-3" id="cell-field" style="display:none;">
    <label for="cell" class="form-label">Cell</label>
    <select class="form-control" id="cell" name="cell"></select>
</div>

<div class="mb-3" id="village-field" style="display:none;">
    <label for="village" class="form-label">Village</label>
    <select class="form-control" id="village" name="village"></select>
</div>

<!-- Salary Information -->
<div class="mb-3">
    <label for="gross_salary" class="form-label">Gross Salary</label>
    <input type="number" class="form-control" id="gross_salary" name="gross_salary">
</div>

<!-- Contract Information -->
<div class="mb-3">
    <label for="contract" class="form-label">Contract Type</label>
    <select class="form-control" id="contract" name="contract">
        <?php 
        $sql = "SELECT * FROM tbl_contract_type";
        $result = $conn->query($sql);
        while ($row = $result->fetch()) { ?>
            <option value="<?php echo $row['contr_type_id']; ?>"><?php echo $row['contr_name']; ?></option>
        <?php } ?>
    </select>
</div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="edit_employee_card">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
                    if(data.status == 200) {
                        // Populate personal info
                        $('#emp_id').val(empId);
                        $('#staff_id').val(data.employee.staff_id);
                        $('#first_name').val(data.employee.first_name);
                        $('#family_name').val(data.employee.family_name);
                        $('#gender').val(data.employee.gender);
                        $('#dob').val(data.employee.dob);
                        $('#marital_status').val(data.employee.marital_status);
                        $('#nationality').val(data.employee.nationality);
                        $('#nid').val(data.employee.nid);
                        $('#phone').val(data.employee.phone);
                        $('#email').val(data.employee.email);
                        
                        // Handle address fields
                        $('#country').val(data.employee.country).trigger('change');
                        if(data.employee.country == 160) {
                            // Rwanda - show all address fields
                            $('#province').val(data.employee.province).trigger('change');
                            setTimeout(function() {
                                $('#district').val(data.employee.district).trigger('change');
                                setTimeout(function() {
                                    $('#sector').val(data.employee.sector);
                                    $('#cell').val(data.employee.cell);
                                    $('#village').val(data.employee.village);
                                }, 300);
                            }, 300);
                        } else {
                            // Other countries - hide some fields
                            $('#province, #district, #sector, #cell, #village').val('');
                        }
                        
                        // Family info
                        $('#mother_name').val(data.employee.mother_name);
                        $('#father_name').val(data.employee.father_name);
                        
                        // Financial info
                        $('#bank').val(data.employee.bank);
                        $('#acc_no').val(data.employee.acc_no);
                        $('#rssb').val(data.employee.rssb);
                        
                        // Employment info
                        $('#post').val(data.post.post);
                        $('#campus').val(data.employee.campus);
                        
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
    
    
    
    
    
    
    // Province to District
$('#province').change(function() {
    var provinceCode = $(this).val();
    if(provinceCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_districts",
                provincecode: provinceCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#district').empty().append('<option value="">Select District</option>');
                $.each(data, function(key, value) {
                    $('#district').append('<option value="'+value.districtcode+'">'+value.namedistrict+'</option>');
                });
            },
            error: function() {
                pop_wrong("Failed to load districts");
            }
        });
    }
});

// District to Sector
$('#district').change(function() {
    var districtCode = $(this).val();
    if(districtCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_sectors",
                districtcode: districtCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#sector').empty().append('<option value="">Select Sector</option>');
                $.each(data, function(key, value) {
                    $('#sector').append('<option value="'+value.sectorcode+'">'+value.namesector+'</option>');
                });
            },
            error: function() {
                pop_wrong("Failed to load sectors");
            }
        });
    }
});

// Sector to Cell
$('#sector').change(function() {
    var sectorCode = $(this).val();
    if(sectorCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_cells",
                sectorcode: sectorCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#cell').empty().append('<option value="">Select Cell</option>');
                $.each(data, function(key, value) {
                    $('#cell').append('<option value="'+value.codecell+'">'+value.nameCell+'</option>');
                });
            },
            error: function() {
                pop_wrong("Failed to load cells");
            }
        });
    }
});

// Cell to Village
$('#cell').change(function() {
    var cellCode = $(this).val();
    if(cellCode) {
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: {
                action: "get_villages",
                codecell: cellCode
            },
            dataType: "JSON",
            success: function(data) {
                $('#village').empty().append('<option value="">Select Village</option>');
                $.each(data, function(key, value) {
                    $('#village').append('<option value="'+value.CodeVillage+'">'+value.VillageName+'</option>');
                });
            },
            error: function() {
                pop_wrong("Failed to load villages");
            }
        });
    }
});


    // Handle country change to show/hide Rwanda address fields
    $('#country').change(function() {
        if($(this).val() == 160) {
            $('#province, #district, #sector, #cell, #village').closest('.mb-3').show();
        } else {
            $('#province, #district, #sector, #cell, #village').closest('.mb-3').hide();
        }
    });
});







// change handler to show/hide Rwanda address fields
$('#country').change(function() {
    if($(this).val() == 160) { // Rwanda
        $('#province, #district, #sector, #cell, #village').closest('.mb-3').show();
    } else {
        $('#province, #district, #sector, #cell, #village').closest('.mb-3').hide();
    }
});
</script>

the following is my php part

else if ($action == "get_employee_data") {
    $id = $_POST['emp_id'];
    
    try {
        // Get main staff info
        $stmt = $conn->prepare("SELECT * FROM tbl_staff_info WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($employee) {
            // Get salary info
            $stmt = $conn->prepare("SELECT * FROM tbl_staff_salary WHERE staff_Id = :staff_id");
            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $salary = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get post info
            $stmt = $conn->prepare("SELECT * FROM tbl_staff_post WHERE staff_id = :staff_id");
            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get user info
            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE Identification = :staff_id");
            $stmt->bindParam(':staff_id', $employee['staff_id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $data = array(
                "status" => "200", 
                "employee" => $employee,
                "salary" => $salary,
                "post" => $post,
                "user" => $user
            );
        } else {
            $data = array("status" => "404", "message" => "Employee not found");
        }
    } catch (PDOException $e) {
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
}



// Complete Employee Update Function
else if ($action == "edit_employee_card") {
    $id = $_POST['emp_id'];
    
    try {
        $conn->beginTransaction();
        
        // 1. Update tbl_staff_info
        $updateStaff = [
            'staff_id' => $_POST['staff_id'],
            'first_name' => $_POST['first_name'],
            'family_name' => $_POST['family_name'],
            'gender' => $_POST['gender'],
            'dob' => $_POST['dob'],
            'marital_status' => $_POST['marital_status'],
            'nationality' => $_POST['nationality'],
            'nid' => $_POST['nid'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'country' => $_POST['country'],
            'province' => ($_POST['country'] == 160) ? $_POST['province'] : null,
            'district' => ($_POST['country'] == 160) ? $_POST['district'] : null,
            'sector' => ($_POST['country'] == 160) ? $_POST['sector'] : null,
            'cell' => ($_POST['country'] == 160) ? $_POST['cell'] : null,
            'village' => ($_POST['country'] == 160) ? $_POST['village'] : null,
            'mother_name' => $_POST['mother_name'],
            'father_name' => $_POST['father_name'],
            'bank' => $_POST['bank'],
            'acc_no' => $_POST['acc_no'],
            'rssb' => $_POST['rssb'],
            'campus' => $_POST['campus'],
            'id' => $id
        ];
        
        $sql = "UPDATE tbl_staff_info SET ";
        $params = [];
        foreach ($updateStaff as $field => $value) {
            if ($field != 'id') {
                $sql .= "$field = :$field, ";
                $params[":$field"] = $value;
            }
        }
        $sql = rtrim($sql, ", ") . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        // 2. Update tbl_staff_salary
        if(isset($_POST['gross_salary'])) {
            $updateSalary = [
                'staff_Id' => $_POST['staff_id'],
                'gross_salary' => $_POST['gross_salary'],
                'starting_date' => date('Y-m-d') // or get from form if available
            ];
            
            // Check if record exists
            $check = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_salary WHERE staff_Id = :staff_Id");
            $check->bindParam(':staff_Id', $_POST['staff_id']);
            $check->execute();
            
            if($check->fetchColumn() > 0) {
                // Update existing
                $sql = "UPDATE tbl_staff_salary SET gross_salary = :gross_salary, starting_date = :starting_date WHERE staff_Id = :staff_Id";
            } else {
                // Insert new
                $sql = "INSERT INTO tbl_staff_salary (staff_Id, gross_salary, starting_date) VALUES (:staff_Id, :gross_salary, :starting_date)";
            }
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($updateSalary);
        }
        
        // 3. Update tbl_staff_post
        $updatePost = [
            'staff_id' => $_POST['staff_id'],
            'is_acadmic' => $_POST['is_academic'] ?? null,
            'acad_grad_id' => $_POST['acad_grad_id'] ?? null,
            'department' => $_POST['department'] ?? null,
            'post' => $_POST['post'],
            'basic_salary' => $_POST['basic_salary'] ?? null,
            'rssb' => $_POST['rssb'] ?? null,
            'contract' => $_POST['contract'],
            'campus' => $_POST['campus'],
            'probation_period' => $_POST['probation_period'] ?? null,
            'join_date' => $_POST['join_date'] ?? date('Y-m-d')
        ];
        
        // Check if record exists
        $check = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_post WHERE staff_id = :staff_id");
        $check->bindParam(':staff_id', $_POST['staff_id']);
        $check->execute();
        
        if($check->fetchColumn() > 0) {
            // Update existing
            $sql = "UPDATE tbl_staff_post SET 
                    is_acadmic = :is_acadmic, 
                    acad_grad_id = :acad_grad_id,
                    department = :department,
                    post = :post,
                    basic_salary = :basic_salary,
                    rssb = :rssb,
                    contract = :contract,
                    campus = :campus,
                    probation_period = :probation_period,
                    join_date = :join_date
                    WHERE staff_id = :staff_id";
        } else {
            // Insert new
            $sql = "INSERT INTO tbl_staff_post 
                    (staff_id, is_acadmic, acad_grad_id, department, post, basic_salary, rssb, contract, campus, probation_period, join_date) 
                    VALUES 
                    (:staff_id, :is_acadmic, :acad_grad_id, :department, :post, :basic_salary, :rssb, :contract, :campus, :probation_period, :join_date)";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($updatePost);
        
        // 4. Update tbl_users
        if(isset($_POST['email'])) {
            $updateUser = [
                'Identification' => $_POST['staff_id'],
                'family_name' => $_POST['family_name'],
                'first_name' => $_POST['first_name'],
                'email' => $_POST['email'],
                'phone_no' => $_POST['phone'],
                'role_id' => $_POST['role_id'] ?? null
            ];
            
            $sql = "UPDATE tbl_users SET 
                    family_name = :family_name,
                    first_name = :first_name,
                    email = :email,
                    phone_no = :phone_no,
                    role_id = :role_id
                    WHERE Identification = :Identification";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($updateUser);
        }
        
        $conn->commit();
        $data = array("status" => "200", "message" => "Employee updated successfully!");
    } catch (PDOException $e) {
        $conn->rollBack();
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}



else if ($action == "get_districts") {
    $provincecode = $_POST['provincecode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM districts WHERE provincecode = :provincecode ORDER BY namedistrict");
        $stmt->bindParam(':provincecode', $provincecode);
        $stmt->execute();
        
        $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($districts);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get sectors by district
else if ($action == "get_sectors") {
    $districtcode = $_POST['districtcode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM sectors WHERE districtcode = :districtcode ORDER BY namesector");
        $stmt->bindParam(':districtcode', $districtcode);
        $stmt->execute();
        
        $sectors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($sectors);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get cells by sector
else if ($action == "get_cells") {
    $sectorcode = $_POST['sectorcode'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM cells WHERE sectorcode = :sectorcode ORDER BY nameCell");
        $stmt->bindParam(':sectorcode', $sectorcode);
        $stmt->execute();
        
        $cells = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cells);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}

// Get villages by cell
else if ($action == "get_villages") {
    $codecell = $_POST['codecell'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM villages WHERE codecell = :codecell ORDER BY VillageName");
        $stmt->bindParam(':codecell', $codecell);
        $stmt->execute();
        
        $villages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($villages);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Database error: " . $e->getMessage()));
    }
    exit;
}


=>4

Updating is not waorking


<!-- Modal Structure -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmployeeModalLabel">Update Employee</h5>
        <button type="button" class="btn-close btn btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"> <i class="fa-solid fa-xmark"></i> </button>
      </div>
      <div class="modal-body">
        <form id="edit_employee_card" method="POST">
          <input type="hidden" name="emp_id" id="emp_id">
          <input type="hidden" name="action" value="edit_employee_card">
          
          <div class="row">
            <!-- Personal Information -->
            <div class="col-md-6">
              <h5>Personal Information</h5>
              <div class="mb-3">
                <label for="staff_id" class="form-label">Staff ID</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" readonly>
              </div>
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
              </div>
              
       
              
              <div class="mb-3">
                <label for="family_name" class="form-label">Family Name</label>
                <input type="text" class="form-control" id="family_name" name="family_name" required>
              </div>
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control select2" style="width:100%" id="gender" name="gender" required>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
              </div>
              <div class="mb-3">
                <label for="marital_status" class="form-label">Marital Status</label>
                <select class="form-control select2" style="width:100%" id="marital_status" name="marital_status">
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Divorced">Divorced</option>
                  <option value="Widowed">Widowed</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="nid" class="form-label">National ID</label>
                <input type="text" class="form-control" id="nidId" name="nid">
              </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-6">
              <h5>Contact Information</h5>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phoneeee" name="phone">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="emaillll" name="email">
              </div>
              
              <h5 class="mt-4">Address Information</h5>
              <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-control select2" style="width:100%" id="country" name="country">
                  <?php 
                  $sql = "SELECT * FROM tbl_country";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['cntr_id']; ?>"><?php echo $row['cntr_nationality']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="province" class="form-label">Province</label>
                <select class="form-control select2" style="width:100%" id="province" name="province">
                  <?php 
                  $sql = "SELECT * FROM provinces";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['provincecode']; ?>"><?php echo $row['provincename']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="district" class="form-label">District</label>
                <input type="text" class="form-control" id="district" name="district">
              </div>
              <div class="mb-3">
                <label for="sector" class="form-label">Sector</label>
                <input type="text" class="form-control" id="sector" name="sector">
              </div>
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Additional Information -->
            <div class="col-md-6">
              <h5>Family Information</h5>
              <div class="mb-3">
                <label for="mother_name" class="form-label">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name">
              </div>
              <div class="mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name">
              </div>
            </div>
            
            <div class="col-md-6">
              <h5>Employment Information</h5>
              <div class="mb-3">
                <label for="post" class="form-label">Position</label>
                <select class="form-control select2" style="width:100%" id="post" name="post" required>
                  <?php 
                  $sql = "SELECT * FROM staff_post";
                  $result = $conn->query($sql);
                  ?>
                  <option value="" selected disabled>--Choose One--</option>
                  <?php while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['staff_post_id']; ?>"><?php echo $row['staff_post_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="campus" class="form-label">Campus</label>
                <select class="form-control select2" style="width:100%" id="campus" name="campus">
                  <?php 
                  $sql = "SELECT * FROM tbl_campus";
                  $result = $conn->query($sql);
                  while ($row = $result->fetch()) { ?>
                    <option value="<?php echo $row['camp_id']; ?>"><?php echo $row['camp_full_name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              
            </div>
          </div>
          
          <div class="row mt-3">
            <!-- Financial Information -->
            <div class="col-md-6">
              <h5>Financial Information</h5>
              <div class="mb-3">
                <label for="bank" class="form-label">Bank Name</label>
                <input type="text" class="form-control" id="bank" name="bank">
              </div>
              <div class="mb-3">
                <label for="acc_no" class="form-label">Account Number</label>
                <input type="text" class="form-control" id="acc_no" name="acc_no">
              </div>
              <div class="mb-3">
                <label for="rssb" class="form-label">RSSB Number</label>
                <input type="text" class="form-control" id="rssbms" name="rssb">
              </div>
            </div>
            
            <!-- Nationality -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nationality" class="form-label">Nationality</label>
                <select class="form-control select2" style="width:100%" id="nationality" name="nationality">
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
          
          <!-- Add these inside your form where appropriate -->
<div class="mb-3" id="cell-field" style="display:none;">
    <label for="cell" class="form-label">Cell</label>
    <select class="form-control select2" style="width:100%" id="cell" name="cell"></select>
</div>

<div class="mb-3" id="village-field" style="display:none;">
    <label for="village" class="form-label">Village</label>
    <select class="form-control select2" style="width:100%" id="village" name="village"></select>
</div>

<!-- Salary Information -->
<div class="mb-3">
    <label for="gross_salary" class="form-label">Gross Salary</label>
    <input type="number" class="form-control" id="gross_salary" name="gross_salary">
</div>

<!-- Contract Information -->
<div class="mb-3">
    <label for="contract" class="form-label">Contract Type</label>
    <select class="form-control select2" style="width:100%" id="contract" name="contract">
        <?php 
        $sql = "SELECT * FROM tbl_contract_type";
        $result = $conn->query($sql);
        while ($row = $result->fetch()) { ?>
            <option value="<?php echo $row['contr_type_id']; ?>"><?php echo $row['contr_name']; ?></option>
        <?php } ?>
    </select>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="edit_employee_card">Save changes</button>
      </div>
        </form>
      </div>
      
    </div>
  </div>
</div>



    $("#edit_employee_card").submit(function(e) {
    e.preventDefault();
    $('.spinner81').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    var formData = new FormData(this);
    
    $.ajax({
        type: "POST",
        url: "/files/Employees/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: function(data) {
            $('.spinner81').fadeOut('fast');
            if (data.status == 200) {
                pop_up_success(data.message);
                $('#employees_table').load(location.href + " #employees_table");
                $('#editEmployeeModal').modal('hide');
            }
            if (data.status == 500) {
                pop_wrong(data.message);
            }
        },
        error: function() {
            $('.spinner81').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});
    
else if ($action == "edit_employee_card") {
    $id = $_POST['emp_id'];
    
    try {
        $conn->beginTransaction();
        
        // 1. Update tbl_staff_info
        $updateStaff = [
            'staff_id' => $_POST['staff_id'],
            'first_name' => $_POST['first_name'],
            'family_name' => $_POST['family_name'],
            'gender' => $_POST['gender'],
            'dob' => $_POST['dob'],
            'marital_status' => $_POST['marital_status'],
            'nationality' => $_POST['nationality'],
            'nid' => $_POST['nid'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'country' => $_POST['country'],
            'province' => ($_POST['country'] == 160) ? $_POST['province'] : null,
            'district' => ($_POST['country'] == 160) ? $_POST['district'] : null,
            'sector' => ($_POST['country'] == 160) ? $_POST['sector'] : null,
            'cell' => ($_POST['country'] == 160) ? $_POST['cell'] : null,
            'village' => ($_POST['country'] == 160) ? $_POST['village'] : null,
            'mother_name' => $_POST['mother_name'],
            'father_name' => $_POST['father_name'],
            'bank' => $_POST['bank'],
            'acc_no' => $_POST['acc_no'],
            'rssb' => $_POST['rssb'],
            'campus' => $_POST['campus'],
            'id' => $id
        ];
        
        $sql = "UPDATE tbl_staff_info SET ";
        $params = [];
        foreach ($updateStaff as $field => $value) {
            if ($field != 'id') {
                $sql .= "$field = :$field, ";
                $params[":$field"] = $value;
            }
        }
        $sql = rtrim($sql, ", ") . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        // 2. Update tbl_staff_salary
        if(isset($_POST['gross_salary'])) {
            $updateSalary = [
                'staff_Id' => $_POST['staff_id'],
                'gross_salary' => $_POST['gross_salary'],
                'starting_date' => date('Y-m-d') // or get from form if available
            ];
            
            // Check if record exists
            $check = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_salary WHERE staff_Id = :staff_Id");
            $check->bindParam(':staff_Id', $_POST['staff_id']);
            $check->execute();
            
            if($check->fetchColumn() > 0) {
                // Update existing
                $sql = "UPDATE tbl_staff_salary SET gross_salary = :gross_salary, starting_date = :starting_date WHERE staff_Id = :staff_Id";
            } else {
                // Insert new
                $sql = "INSERT INTO tbl_staff_salary (staff_Id, gross_salary, starting_date) VALUES (:staff_Id, :gross_salary, :starting_date)";
            }
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($updateSalary);
        }
        
        // 3. Update tbl_staff_post
        $updatePost = [
            'staff_id' => $_POST['staff_id'],
            'post' => $_POST['post'],
            'contract' => $_POST['contract'],
            'basic_salary' => $_POST['basic_salary'] ?? null,
            'rssb' => $_POST['rssb'] ?? null,
            'campus' => $_POST['campus'],
            'id' => $id
        ];
        
        // Check if record exists
        $check = $conn->prepare("SELECT COUNT(*) FROM tbl_staff_post WHERE staff_id = :staff_id");
        $check->bindParam(':staff_id', $_POST['staff_id']);
        $check->execute();
        
        if($check->fetchColumn() > 0) {
            // Update existing
            $sql = "UPDATE tbl_staff_post SET 
                    post = :post,
                    contract = :contract,
                    basic_salary = :basic_salary,
                    rssb = :rssb,
                    campus = :campus
                    WHERE staff_id = :staff_id";
        } else {
            // Insert new
            $sql = "INSERT INTO tbl_staff_post 
                    (staff_id, post, contract, basic_salary, rssb, campus) 
                    VALUES 
                    (:staff_id, :post, :contract, :basic_salary, :rssb, :campus)";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($updatePost);
        
        // 4. Update tbl_users
        if(isset($_POST['email'])) {
            $updateUser = [
                'Identification' => $_POST['staff_id'],
                'family_name' => $_POST['family_name'],
                'first_name' => $_POST['first_name'],
                'email' => $_POST['email'],
                'phone_no' => $_POST['phone']
            ];
            
            $sql = "UPDATE tbl_users SET 
                    family_name = :family_name,
                    first_name = :first_name,
                    email = :email,
                    phone_no = :phone_no
                    WHERE Identification = :Identification";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($updateUser);
        }
        
        $conn->commit();
        $data = array("status" => "200", "message" => "Employee updated successfully!");
    } catch (PDOException $e) {
        $conn->rollBack();
        $data = array("status" => "500", "message" => "Database error: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


tbl_staff_info 
    (staff_id, family_name, first_name, gender, dob, marital_status, nationality(fk tbl_nationality.nat_id), nid, phone, 
     email, staff_image, country(fk tbl_country.cntr_id), province(fk provinces.provincecode),
     district(fk districts.districtcode), sector(fk sectors.sectorcode), cell(fk cells. codecell), village(fk villages.CodeVillage), mother_name, 
     father_name, bank, acc_no, rssb, campus(fk))		

tbl_staff_salary 
            (staff_Id, gross_salary, starting_date)	

tbl_staff_post 
            (staff_id, is_acadmic(fk tbl_staff_type.staff_type_id), acad_grad_id(fk tbl_acad_grade.acad_grad_id), 
            department(fk tbl_department.dept_id), post(fk staff_post.staff_post_id), basic_salary, rssb, contract(fk tbl_contract_type.contr_type_id), 
             campus(fk tbl_campus.camp_full_name), probation_period, join_date)	

tbl_users 
            (Identification(staff_id), family_name, first_name, email, phone_no, password, role_id(fk tbl_user_roles.role_id))
            
tbl_nationality (nat_id, nationality)

tbl_country (cntr_id, cntr_name)

provinces (provincecode, provincename)
districts (districtcode, namedistrict, provincecode)
sectors (sectorcode, namesector, districtcode)
cells (codecell, nameCell, sectorcode)
villages (CodeVillage, VillageName, codecell)

tbl_campus (camp_id, camp_full_name)

tbl_staff_type(staff_type_id, staff_type_full_name)
tbl_acad_grade (acad_grad_id, acad_grad_full_name)
tbl_department (dept_id, dept_full_name)
staff_post (staff_post_id, staff_post_full_name)
tbl_contract_type (contr_type_id, contr_name)
tbl_user_roles (role_id, role)
