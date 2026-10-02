<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Student registration</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            </div>
        </div>
       <div class="section-body">
           <div class="row">
                <div class="col-12 col-md-12 col-lg-12" style="margin:auto;">
                    <form id="register_student" action="register_student" method="POST">
                        <input type="hidden" name="action" value="register_new">
                        <div class="card">
                            <div class="card-header row" style="display:flex; justify-content:center">
                                <a  class="btn btn-light col-12 col-md-2 col-lg-2" style="margin-bottom:10px;" href="edu?mis=view_stu"><i class="fas fa-clipboard"></i>&nbsp;Students - Review</a>
                                
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="e_camp_id" required>
                                                                <option selected disabled>Select Campus</option>
                                                                <?php
                                                                    $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                                <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            &nbsp;<span id="spinner0"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Program</label>
                                                    <select class="form-control select2" style="width:100%;" name="prg_type_id" id="e_prg_type_id" required>
                                                        <option selected Disabled>Select Program</option>
                                                    </select>
                                                    &nbsp;<span id="spinner1"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="e_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="e_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="e_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Mode</label>
                                                     <select class="form-control select2" style="width:100%;" name="prg_mode_id" id="e_prg_mode_id" required>
                                                     <option selected disabled>Select Mode</option>
                                                                
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                     <label>Academic Year</label>
                                                     <select class="form-control select2" style="width:100%;" name="acad_cycle_id" id="acad_cycle_id" required>
                                                     <option selected disabled>Select Academic Year</option>
                                                    <?php
                                                    $select="SELECT * FROM tbl_acad_cycle order by status asc";
                                                    $cselect=$conn->prepare($select);
                                                    $cselect->execute();
                                                    foreach($cselect as $row_cselect){
                                                    
                                                    ?>
                                                    <option value="<?php echo $row_cselect['acad_cycle_id'];?>"><?php echo $row_cselect['acad_year'];?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    
                                                    </select>
                                                </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" id="downloadButton" class="btn btn-success form-control">Download IDs in Excel</button>
                                            </div>
                                            </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                            <label>&nbsp;</label>
                                            <input type="file" id="csvFileInput" accept=".csv" class="form-control mb-2">
                                            <button type="button" id="uploadButton" class="btn btn-primary form-control">Upload CSV</button>
                                            </div>
                                            </div>
                            </div>
                        </div>
                    </form>
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
    $('#downloadButton').on('click', function() {
    // Get selected values from the form
    const Campus = $('#e_camp_id').val();
    const Program = $('#e_prg_type_id').val();
    const School = $('#e_fac_id').val();
    const Department = $('#e_dept_id').val();
    const Specialization = $('#e_splz_id').val();
    const Level = $('#e_level_id').val();
    const Mode = $('#e_prg_mode_id').val();
    const Academic = $('#acad_cycle_id').val();

    // Validate inputs
    if (!Campus || !Program || !School || !Department || !Specialization || !Level || !Mode || !Academic) {
        pop_wrong("Please fill all required fields before downloading.");
        return;
    }

    // Send data to the backend
    $.ajax({
        url: "/files/Student/student_controller.php",
        type: "POST",
        data: { 
            action: 'download_form_data', 
            camp_id: Campus, 
            prg_type_id: Program, 
            fac_id: School, 
            dept_id: Department,
            splz_id: Specialization,
            level_id: Level,
            prg_mode_id: Mode,
            acad_cycle_id: Academic
        },
        dataType: "JSON",
        success: function(data) {
            if (data.status === 200) {
                // Prepare CSV content
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "FirstName,LastName,Gender,Admission No.\n";
                csvContent += ` , , , \n`;

                // Trigger the download
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "form_data.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                pop_wrong("Error downloading data.");
            }
        },
        error: function() {
            pop_wrong("Something went wrong during download!");
        }
    });
});

// CSV Upload with Form Data
$('#uploadButton').on('click', function() {
    const fileInput = document.getElementById('csvFileInput');
    const file = fileInput.files[0];

    // Get selected form values
    const Campus = $('#e_camp_id').val();
    const Program = $('#e_prg_type_id').val();
    const School = $('#e_fac_id').val();
    const Department = $('#e_dept_id').val();
    const Specialization = $('#e_splz_id').val();
    const Level = $('#e_level_id').val();
    const Mode = $('#e_prg_mode_id').val();
    const Academic = $('#acad_cycle_id').val();

    // Validate inputs
    if (!Campus || !Program || !School || !Department || !Specialization || !Level || !Mode || !Academic) {
        pop_wrong("Please fill all required fields before uploading.");
        return;
    }

    if (!file) {
        pop_wrong("Please select a CSV file to upload.");
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const csvData = e.target.result;
        const rows = csvData.split("\n").slice(1); // Skip header row

        const formData = new FormData();
        formData.append("action", "upload_csv_data");
        formData.append("csv_data", JSON.stringify(rows)); // Send rows as JSON

        // Include selected form values
        formData.append("camp_id", Campus);
        formData.append("prg_type_id", Program);
        formData.append("fac_id", School);
        formData.append("dept_id", Department);
        formData.append("splz_id", Specialization);
        formData.append("level_id", Level);
        formData.append("prg_mode_id", Mode);
        formData.append("acad_cycle_id", Academic);

        // Disable button to prevent multiple clicks
        $('#uploadButton').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(response) {
                if (response.status === 200) {
                    pop_up_success("Data uploaded and inserted successfully.");
                } else {
                    pop_wrong("Failed to insert data.");
                }
            },
            error: function() {
                pop_wrong("Something went wrong during upload.");
            },
            complete: function() {
                // Re-enable button after upload
                $('#uploadButton').prop('disabled', false).text('Upload CSV');
            }
        });
    };
    reader.readAsText(file);
});

// Step 1: When Campus is selected     
        $("#e_camp_id").change(function () {
        var campus = $("#e_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#e_prg_type_id').css({'display':'none'});
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for programs
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner0').fadeOut('fast');
                $('#e_prg_type_id').css({'display':'block'}); // Show program section

                // Clear existing program options
                $("#e_prg_type_id").empty();
                $("#e_prg_type_id").append("<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 2: When Program is selected   
$("#e_prg_type_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#e_fac_id').css({'display':'none'});
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for schools
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner1').fadeOut('fast');
                $('#e_fac_id').css({'display':'block'}); // Show school section

                // Clear existing school options
                $("#e_fac_id").empty();
                $("#e_fac_id").append("<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 3: When School is selected
    $("#e_fac_id").change(function () {
        var school = $("#e_fac_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#e_dept_id').css({'display':'none'});
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner2').fadeOut('fast');
                $('#e_dept_id').css({'display':'block'}); // Show department section

                // Clear existing department options
                $("#e_dept_id").empty();
                $("#e_dept_id").append("<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 4: When Department is selected
    $("#e_dept_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var school = $("#e_fac_id").val();
        
        var formdata = {
            dept_id: department,
            prg_type:program,
            fac_id:school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#e_splz_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner3').fadeOut('fast');
                $('#e_splz_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_splz_id").empty();
                $("#e_splz_id").append("<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 5: When Specialization is selected
    $("#e_splz_id").change(function () {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type:program,
            action: "load_levels"
        };

        // Hide level section initially
        $('#e_level_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner4').fadeOut('fast');
                $('#e_level_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#e_level_id").empty();
                $("#e_level_id").append("<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    // Step 6: When Level is selected
    $("#e_level_id").change(function () {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_type:program,
            action: "load_modes"
        };

        // Hide level section initially
        $('#e_prg_mode_id').css({'display':'none'});

        // Show loading spinner for levels
        $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner5').fadeOut('fast');
                $('#e_prg_mode_id').css({'display':'block'}); // Show level section
                $('#appBtn').css({'display':'block'});

                // Clear existing level options
                $("#e_prg_mode_id").empty();
                $("#e_prg_mode_id").append("<option value='' disabled selected>Select Mode</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#e_prg_mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner5').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    
    


        
    
        
});
   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
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