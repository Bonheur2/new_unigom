<?php
include('../../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle CSV template download
if (isset($_GET['download_template'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="specialization_template.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    // Write CSV header
    fputcsv($output, array('Specialization Full Name', 'Specialization Short Name', 'Degree Name', 'Diploma Name'));
    
    // Write sample data
    fputcsv($output, array('Computer Science Specialization', 'CS SPEC', 'Bachelor of Science', 'CS Diploma'));
    fputcsv($output, array('Software Engineering Specialization', 'SE SPEC', 'Bachelor of Engineering', 'SE Diploma'));
    fputcsv($output, array('Data Science Specialization', 'DS SPEC', 'Bachelor of Science', 'DS Diploma'));
    
    fclose($output);
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $campus_id = $_POST['campus'];
    $program_type_id = $_POST['program_type'];
    $faculty_id = $_POST['faculty'];
    $department_id = $_POST['department'];
    
    if ($_FILES['csv_file']['error'] == 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $success_count = 0;
            $error_count = 0;
            $errors = array();
            
            // Skip header row if exists
            $header = fgetcsv($handle, 1000, ",");
            
            // Prepare the insert statement using PDO
            $sql = "INSERT INTO tbl_specialization (prg_type, fac_id, dept_id, splz_full_name, splz_short_name, degree_name, diploma_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming CSV columns: splz_full_name, splz_short_name, degree_name, diploma_name
                if (count($data) >= 4) {
                    $splz_full_name = strtoupper(trim($data[0]));
                    $splz_short_name = strtoupper(trim($data[1]));
                    $degree_name = strtoupper(trim($data[2]));
                    $diploma_name = strtoupper(trim($data[3]));
                    
                    // Check for duplicate specialization full name within the same department
                    $duplicate_check = "SELECT COUNT(*) FROM tbl_specialization WHERE splz_full_name = ? AND dept_id = ? AND prg_type = ? AND fac_id = ?";
                    $duplicate_stmt = $db->prepare($duplicate_check);
                    $duplicate_stmt->execute([$splz_full_name, $department_id, $program_type_id, $faculty_id]);
                    $duplicate_count = $duplicate_stmt->fetchColumn();
                    
                    if ($duplicate_count > 0) {
                        $error_count++;
                        $errors[] = "Row " . ($success_count + $error_count) . ": Specialization with name '$splz_full_name' already exists in this department";
                        continue;
                    }
                    
                    try {
                        $stmt->execute([$program_type_id, $faculty_id, $department_id, $splz_full_name, $splz_short_name, $degree_name, $diploma_name]);
                        $success_count++;
                    } catch (PDOException $e) {
                        $error_count++;
                        $errors[] = "Row " . ($success_count + $error_count) . ": " . $e->getMessage();
                    }
                } else {
                    $error_count++;
                    $errors[] = "Row " . ($success_count + $error_count) . ": Insufficient data columns";
                }
            }
            fclose($handle);
            
            $message = "Upload completed! $success_count records inserted successfully.";
            if ($error_count > 0) {
                $message .= " $error_count errors occurred.";
            }
        } else {
            $message = "Error reading the CSV file.";
        }
    } else {
        $message = "Error uploading file.";
    }
}

// Handle AJAX request for program types based on campus
if (isset($_POST['get_programs']) && isset($_POST['campus_id'])) {
    $campus_id = $_POST['campus_id'];
    $program_types_query = "SELECT `prg_type_id`, `prg_type_full_name`, `prg_type_short_name` FROM `tbl_program_type` WHERE campus_id = ? AND status = '1'";
    $program_types_stmt = $db->prepare($program_types_query);
    $program_types_stmt->execute([$campus_id]);
    $program_types_result = $program_types_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($program_types_result);
    exit();
}

// Handle AJAX request for faculties based on program type
if (isset($_POST['get_faculties']) && isset($_POST['program_type_id'])) {
    $program_type_id = $_POST['program_type_id'];
    $faculties_query = "SELECT `fac_id`, `fac_full_name`, `fac_short_name` FROM `tbl_faculty` WHERE prg_type = ? AND status = '1'";
    $faculties_stmt = $db->prepare($faculties_query);
    $faculties_stmt->execute([$program_type_id]);
    $faculties_result = $faculties_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($faculties_result);
    exit();
}

// Handle AJAX request for departments based on faculty
if (isset($_POST['get_departments']) && isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];
    $departments_query = "SELECT `dept_id`, `dept_full_name`, `dept_short_name` FROM `tbl_department` WHERE fac_id = ? AND status = '1'";
    $departments_stmt = $db->prepare($departments_query);
    $departments_stmt->execute([$faculty_id]);
    $departments_result = $departments_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($departments_result);
    exit();
}

// Fetch campuses
$campus_query = "SELECT * FROM `tbl_campus` WHERE camp_active = '1'";
$campus_stmt = $db->prepare($campus_query);
$campus_stmt->execute();
$campus_result = $campus_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specialization CSV Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        select, input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .csv-format {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .csv-format h3 {
            margin-top: 0;
            color: #495057;
        }
        .csv-format code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .template-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
        }
        .template-btn:hover {
            background-color: #218838;
            color: white;
            text-decoration: none;
        }
        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-btn {
            background-color: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }
        .nav-btn:hover {
            background-color: #545b62;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation">
            <a href="school.php" class="nav-btn">Faculty Upload</a>
            <a href="department.php" class="nav-btn">Department Upload</a>
            <a href="specialization.php" class="nav-btn" style="background-color: #007bff;">Specialization Upload</a>
        </div>

        <h1>Specialization CSV Upload</h1>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo ($error_count > 0) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
                <?php if (isset($errors) && !empty($errors)): ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="csv-format">
            <h3>CSV File Format</h3>
            <a href="?download_template=1" class="template-btn">📥 Download CSV Template</a>
            <p>Your CSV file should have the following columns in this order:</p>
            <p><code>Specialization Full Name, Specialization Short Name, Degree Name, Diploma Name</code></p>
            <p><strong>Example:</strong></p>
            <p><code>Computer Science Specialization,CS SPEC,Bachelor of Science,CS Diploma</code></p>
            <p><strong>Note:</strong> All text data will be automatically converted to UPPERCASE when uploaded.</p>
            <p><strong>Important:</strong> Specialization names must be unique within each department.</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="campus">Select Campus:</label>
                <select name="campus" id="campus" required>
                    <option value="">-- Select Campus First --</option>
                    <?php foreach ($campus_result as $row): ?>
                        <option value="<?php echo $row['camp_id']; ?>">
                            <?php echo htmlspecialchars($row['camp_full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="program_type">Select Program Type:</label>
                <select name="program_type" id="program_type" required disabled>
                    <option value="">-- Select Campus First --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="faculty">Select School/Faculty:</label>
                <select name="faculty" id="faculty" required disabled>
                    <option value="">-- Select Program Type First --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="department">Select Department:</label>
                <select name="department" id="department" required disabled>
                    <option value="">-- Select Faculty First --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="csv_file">Upload CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>

            <button type="submit" class="btn">Upload Specialization Data</button>
        </form>

        <div style="margin-top: 30px;">
            <h3>Current Specialization Records</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 1px solid #dee2e6; padding: 8px;">ID</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Program Type</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Faculty</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Department</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Specialization</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Short Name</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Degree</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Diploma</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $specialization_query = "SELECT s.*, f.fac_full_name, pt.prg_type_full_name, d.dept_full_name 
                                               FROM tbl_specialization s 
                                               LEFT JOIN tbl_faculty f ON s.fac_id = f.fac_id 
                                               LEFT JOIN tbl_program_type pt ON s.prg_type = pt.prg_type_id 
                                               LEFT JOIN tbl_department d ON s.dept_id = d.dept_id 
                                               ORDER BY s.splz_id DESC LIMIT 10";
                        $specialization_stmt = $db->prepare($specialization_query);
                        $specialization_stmt->execute();
                        $specialization_result = $specialization_stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($specialization_result as $specialization): ?>
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo $specialization['splz_id']; ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['prg_type_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['fac_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['dept_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['splz_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['splz_short_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['totalCredits']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['degree_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($specialization['state']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p style="text-align: center; color: #6c757d; margin-top: 10px;">Showing last 10 records</p>
        </div>
    </div>

    <script>
        // Handle campus selection to load program types
        document.getElementById('campus').addEventListener('change', function() {
            const campusId = this.value;
            const programTypeSelect = document.getElementById('program_type');
            const facultySelect = document.getElementById('faculty');
            const departmentSelect = document.getElementById('department');
            
            if (campusId) {
                // Show loading state
                programTypeSelect.innerHTML = '<option value="">Loading...</option>';
                programTypeSelect.disabled = false;
                
                // Reset other dropdowns
                facultySelect.innerHTML = '<option value="">-- Select Program Type First --</option>';
                facultySelect.disabled = true;
                departmentSelect.innerHTML = '<option value="">-- Select Faculty First --</option>';
                departmentSelect.disabled = true;
                
                // Create FormData for POST request
                const formData = new FormData();
                formData.append('get_programs', '1');
                formData.append('campus_id', campusId);
                
                // Fetch program types via AJAX
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    programTypeSelect.innerHTML = '<option value="">-- Select Program Type --</option>';
                    
                    if (data.length > 0) {
                        data.forEach(program => {
                            const option = document.createElement('option');
                            option.value = program.prg_type_id;
                            option.textContent = `${program.prg_type_full_name} (${program.prg_type_short_name})`;
                            programTypeSelect.appendChild(option);
                        });
                    } else {
                        programTypeSelect.innerHTML = '<option value="">No programs available for this campus</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching program types:', error);
                    programTypeSelect.innerHTML = '<option value="">Error loading programs</option>';
                });
            } else {
                // Reset all dropdowns if no campus selected
                programTypeSelect.innerHTML = '<option value="">-- Select Campus First --</option>';
                programTypeSelect.disabled = true;
                facultySelect.innerHTML = '<option value="">-- Select Program Type First --</option>';
                facultySelect.disabled = true;
                departmentSelect.innerHTML = '<option value="">-- Select Faculty First --</option>';
                departmentSelect.disabled = true;
            }
        });

        // Handle program type selection to load faculties
        document.getElementById('program_type').addEventListener('change', function() {
            const programTypeId = this.value;
            const facultySelect = document.getElementById('faculty');
            const departmentSelect = document.getElementById('department');
            
            if (programTypeId) {
                // Show loading state
                facultySelect.innerHTML = '<option value="">Loading...</option>';
                facultySelect.disabled = false;
                
                // Reset department dropdown
                departmentSelect.innerHTML = '<option value="">-- Select Faculty First --</option>';
                departmentSelect.disabled = true;
                
                // Create FormData for POST request
                const formData = new FormData();
                formData.append('get_faculties', '1');
                formData.append('program_type_id', programTypeId);
                
                // Fetch faculties via AJAX
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    facultySelect.innerHTML = '<option value="">-- Select School/Faculty --</option>';
                    
                    if (data.length > 0) {
                        data.forEach(faculty => {
                            const option = document.createElement('option');
                            option.value = faculty.fac_id;
                            option.textContent = `${faculty.fac_full_name} (${faculty.fac_short_name})`;
                            facultySelect.appendChild(option);
                        });
                    } else {
                        facultySelect.innerHTML = '<option value="">No faculties available for this program type</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching faculties:', error);
                    facultySelect.innerHTML = '<option value="">Error loading faculties</option>';
                });
            } else {
                // Reset faculty and department dropdowns if no program type selected
                facultySelect.innerHTML = '<option value="">-- Select Program Type First --</option>';
                facultySelect.disabled = true;
                departmentSelect.innerHTML = '<option value="">-- Select Faculty First --</option>';
                departmentSelect.disabled = true;
            }
        });

        // Handle faculty selection to load departments
        document.getElementById('faculty').addEventListener('change', function() {
            const facultyId = this.value;
            const departmentSelect = document.getElementById('department');
            
            if (facultyId) {
                // Show loading state
                departmentSelect.innerHTML = '<option value="">Loading...</option>';
                departmentSelect.disabled = false;
                
                // Create FormData for POST request
                const formData = new FormData();
                formData.append('get_departments', '1');
                formData.append('faculty_id', facultyId);
                
                // Fetch departments via AJAX
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
                    
                    if (data.length > 0) {
                        data.forEach(department => {
                            const option = document.createElement('option');
                            option.value = department.dept_id;
                            option.textContent = `${department.dept_full_name} (${department.dept_short_name})`;
                            departmentSelect.appendChild(option);
                        });
                    } else {
                        departmentSelect.innerHTML = '<option value="">No departments available for this faculty</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching departments:', error);
                    departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                });
            } else {
                // Reset department dropdown if no faculty selected
                departmentSelect.innerHTML = '<option value="">-- Select Faculty First --</option>';
                departmentSelect.disabled = true;
            }
        });

        // File size validation
        document.getElementById('csv_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // Convert to MB
                if (fileSize > 5) {
                    alert('File size should be less than 5MB');
                    e.target.value = '';
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const campus = document.getElementById('campus').value;
            const programType = document.getElementById('program_type').value;
            const faculty = document.getElementById('faculty').value;
            const department = document.getElementById('department').value;
            const csvFile = document.getElementById('csv_file').value;

            if (!campus || !programType || !faculty || !department || !csvFile) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>
