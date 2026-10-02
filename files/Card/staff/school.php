<?php
include('../../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle CSV template download
if (isset($_GET['download_template'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="faculty_template.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    
    // Write CSV header
    fputcsv($output, array('Faculty Full Name', 'Faculty Short Name', 'Code'));
    
    // Write sample data
    fputcsv($output, array('Computer Science Faculty', 'CS Faculty', 'CS001'));
    fputcsv($output, array('Information Technology Faculty', 'IT Faculty', 'IT001'));
    fputcsv($output, array('Engineering Faculty', 'ENG Faculty', 'ENG001'));
    
    fclose($output);
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $program_type_id = $_POST['program_type'];
    $campus_id = $_POST['campus'];
    
    if ($_FILES['csv_file']['error'] == 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $success_count = 0;
            $error_count = 0;
            $errors = array();
            
            // Skip header row if exists
            $header = fgetcsv($handle, 1000, ",");
            
            // Prepare the insert statement using PDO
            $sql = "INSERT INTO tbl_faculty (prg_type, fac_full_name, fac_short_name, code) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming CSV columns: fac_full_name, fac_short_name, code
                if (count($data) >= 3) {
                    $fac_full_name = strtoupper(trim($data[0]));
                    $fac_short_name = strtoupper(trim($data[1]));
                    $code = strtoupper(trim($data[2]));
                    
                    // Check for duplicate faculty full name within the same program type
                    $duplicate_check = "SELECT COUNT(*) FROM tbl_faculty WHERE fac_full_name = ? AND prg_type = ?";
                    $duplicate_stmt = $db->prepare($duplicate_check);
                    $duplicate_stmt->execute([$fac_full_name, $program_type_id]);
                    $duplicate_count = $duplicate_stmt->fetchColumn();
                    
                    if ($duplicate_count > 0) {
                        $error_count++;
                        $errors[] = "Row " . ($success_count + $error_count) . ": Faculty with name '$fac_full_name' already exists in this program type";
                        continue;
                    }
                    
                    try {
                        $stmt->execute([$program_type_id, $fac_full_name, $fac_short_name, $code]);
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
    <title>Faculty CSV Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Faculty CSV Upload</h1>
        
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
            <p><code>Faculty Full Name, Faculty Short Name, Code</code></p>
            <p><strong>Example:</strong></p>
            <p><code>Computer Science Faculty,CS Faculty,CS001</code></p>
            <p><strong>Note:</strong> All data will be automatically converted to UPPERCASE when uploaded.</p>
            <p><strong>Important:</strong> Faculty names must be unique within each program type - the same name can exist in different program types but not within the same program type.</p>
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
                <label for="csv_file">Upload CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>

            <button type="submit" class="btn">Upload Faculty Data</button>
        </form>

        <div style="margin-top: 30px;">
            <h3>Current Faculty Records</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 1px solid #dee2e6; padding: 8px;">ID</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Program Type</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Full Name</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Short Name</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Status</th>
                            <th style="border: 1px solid #dee2e6; padding: 8px;">Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $faculty_query = "SELECT f.*, pt.prg_type_full_name 
                                         FROM tbl_faculty f 
                                         LEFT JOIN tbl_program_type pt ON f.prg_type = pt.prg_type_id 
                                         ORDER BY f.fac_id DESC LIMIT 10";
                        $faculty_stmt = $db->prepare($faculty_query);
                        $faculty_stmt->execute();
                        $faculty_result = $faculty_stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($faculty_result as $faculty): ?>
                            <tr>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo $faculty['fac_id']; ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($faculty['prg_type_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($faculty['fac_full_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($faculty['fac_short_name']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($faculty['status']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 8px;"><?php echo htmlspecialchars($faculty['code']); ?></td>
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
            
            if (campusId) {
                // Show loading state
                programTypeSelect.innerHTML = '<option value="">Loading...</option>';
                programTypeSelect.disabled = false;
                
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
                // Reset program type dropdown if no campus selected
                programTypeSelect.innerHTML = '<option value="">-- Select Campus First --</option>';
                programTypeSelect.disabled = true;
            }
        });

        // Add some JavaScript for better user experience
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
            const programType = document.getElementById('program_type').value;
            const campus = document.getElementById('campus').value;
            const csvFile = document.getElementById('csv_file').value;

            if (!programType || !campus || !csvFile) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>