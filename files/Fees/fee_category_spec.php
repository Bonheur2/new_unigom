<!-- Start app main Content -->
<div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Fees Category By Specilization</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard1</a></div>
                                    <div class="breadcrumb-item"><a href="#">Fees Category By Specilization</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <?php if($role_id == 3 || $role_id == 14){ ?>
                                    <div class="col-12 col-sm-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New category</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    
                                                    <form id="save_feecateg_spec" action="save_feecateg_spec" method="POST">
                                                        <input type="hidden" name="action" value="register_fees_spec">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                            <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="u_camp_id" required>
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
                                                            <div class="form-group">
                                                            <label>Program</label>
                                                            <select class="form-control select2" style="width:100%;" name="prg_type_id" id="u_prg_type_id" required>
                                                                <option selected Disabled>Select Program</option>
                                                            </select>
                                                            &nbsp;<span id="spinner1"></span>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="u_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner2"></span>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="u_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            &nbsp;<span id="spinner3"></span>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="u_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            &nbsp;<span id="spinner4"></span>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="u_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            &nbsp;<span id="spinner5"></span>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Category name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="name" name="name" placeholder="name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="amt" >
                                                                <label>Amount</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="Type here" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                            </div>
                                                            
                                                        

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-12 col-sm-8 col-lg-8">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered Categories</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="fee_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                
                                                                <th scope="col">Campus</th>
                                                                <th scope="col">Program</th>
                                                                <th scope="col">School</th>
                                                                <th scope="col">Department</th>
                                                                <th scope="col">Specialization</th>
                                                                <th scope="col">Level</th>
                                                                <th scope="col">Category name</th>
                                                                <th scope="col">Amount</th>
                                                                
                                                                <?php if($role_id == 3 || $role_id == 14){ ?>
                                                                <th scope="col">Action</th>
                                                                <?php } ?>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$i = 1;
$Select_fee = "
    SELECT 
    f.*, 
    ca.camp_full_name, 
    pa.prg_type_full_name, 
    fc.fac_full_name, 
    dp.dept_full_name, 
    sp.splz_full_name, 
    lv.level_full_name
FROM 
    fee_category f
INNER JOIN 
    tbl_campus ca ON f.camp_id = ca.camp_id
INNER JOIN 
    tbl_program_type pa ON f.prg_type_id = pa.prg_type_id
INNER JOIN 
    tbl_faculty fc ON f.fac_id = fc.fac_id
INNER JOIN 
    tbl_department dp ON f.dept_id = dp.dept_id
INNER JOIN 
    tbl_specialization sp ON f.splz_id = sp.splz_id
INNER JOIN 
    tbl_level lv ON f.level_id = lv.level_id
    WHERE specific_status=1
";
$cSelect_fee = $conn->prepare($Select_fee);
$cSelect_fee->execute();

foreach ($cSelect_fee as $row_cSelect_fee) {
?>
    <tr>
        <td><?php echo $i++; ?></td>
        
        <td><?php echo htmlspecialchars($row_cSelect_fee['camp_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['prg_type_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['fac_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['dept_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['splz_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['level_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['amount']); ?></td>
        <?php if($role_id == 3 || $role_id == 14){ ?>
                                                                <th>
                                                                    <div class="buttons row">
                                                                    <button type="button" data-id="<?php echo $row_cSelect_fee['id']; ?>" class="btn btn-icon btn-light btn-sm edit_spec"><span id="spinner4_<?php echo $row_cSelect_fee['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit </button>
                                                                    <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del_spec" data-id="<?php echo $row_cSelect_fee['id']; ?>" <?php echo $row_cSelect_fee['status']==1?'checked':''; ?>>
                                                                        <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $row_cSelect_fee['id']; ?>"></span>&nbsp;
                                                                    </label>
                                                                    </div>
                                                                     
                                                                </th>
                                                                <?php } ?>
    </tr>
<?php
}
?>

                                                            
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                                <!-- Edit Modal -->
                                        <form action="update_form_sp" method="POST" id="update_form_sp">
                                            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Updating Category</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" id="fee_id" name="fee_id">
                                                            <input type="hidden" id="tb_fee" name="tbl_id">
                                                            <input type="hidden" name="action" value="update_fee_spe">
                                                            <div class="form-group">
                                                            <label>Campus</label>
                                                            <select class="form-control select2" style="width:100%;" name="camp_id" id="e_camp_id" required>
                                                                <option selected disabled>Select Campus</option>
                                                                
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Program</label>
                                                            <select class="form-control select2" style="width:100%;" name="prg_type_id" id="e_prg_type_id" required>
                                                                <option selected Disabled>Select Program</option>
                                                                
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>School</label>
                                                            <select class="form-control select2" style="width:100%;" name="fac_id" id="e_fac_id" required>
                                                                <option selected disabled>Select School</option>
                                                                
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Department</label>
                                                            <select class="form-control select2" style="width:100%;" name="dept_id" id="e_dept_id" required>
                                                                <option selected disabled>Select Department</option>
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                <option selected disabled>Select Specialization</option>
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                            <label>Level</label>
                                                            <select class="form-control select2" style="width:100%;" name="level_id" id="e_level_id" required>
                                                                <option selected disabled>Select Level</option>
                                                                
                                                            </select>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Category name</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" id="e_name" name="name" placeholder="name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" id="amt" >
                                                                <label>Amount</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-credit-card"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" id="e_amount" name="amount" placeholder="Type here" required>
                                                                </div>
                                                            </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
</form>

                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
$(document).ready(function(){
        $('#fee_table').DataTable(
         {     
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
        
        $('#downloadButton').on('click', function() {
        // Get selected values from the form
        
        
        
        const Campus = $('#u_camp_id').val();
        const Program = $('#u_prg_type_id').val();
        const School = $('#u_fac_id').val();
        const Department = $('#u_dept_id').val();
        const Specialization = $('#u_splz_id').val();
        const Level = $('#u_level_id').val();
        const CategoryName = $('#name').val();
        const Amount = $('#amount').val();
        
        if (!Campus || !Program || !School || !Department || !Specialization || !Level || !CategoryName  || !Amount) {
            pop_wrong("Please fill all required fields before downloading.");
            return;
        }
        
        // Send data to the backend
        $.ajax({
            url: "/files/Fees/fee_controller.php",
            type: "POST",
            data: { 
                action: 'download_form_data', 
                camp_id: Campus, 
                prg_type_id: Program, 
                fac_id: School, 
                dept_id: Department,
                splz_id:Specialization,
                level_id:Level,
                amount: Amount,
                name: CategoryName
            },
            dataType: "JSON",
            success: function(data) {
                if (data.status === 200) {
                    // Prepare CSV content
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += "Campus,Program,School,Department,Specialization,Level,CategoryName,Amount\n";
                    csvContent += `${data.camp_id},${data.prg_type_id},${data.fac_id},${data.dept_id},${data.splz_id},${data.level_id},${data.name},${data.amount}\n`;

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
    
    $('#uploadButton').on('click', function() {
        const fileInput = document.getElementById('csvFileInput');
        const file = fileInput.files[0];

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

            $.ajax({
                url: "/files/Fees/fee_controller.php",
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
                }
            });
        };
        reader.readAsText(file);
    });
  // Step 1: When Campus is selected     
        $("#u_camp_id").change(function () {
        var campus = $("#u_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#u_prg_type_id').css({'display':'none'});
        $('#u_fac_id').css({'display':'none'});
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

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
                $('#u_prg_type_id').css({'display':'block'}); // Show program section

                // Clear existing program options
                $("#u_prg_type_id").empty();
                $("#u_prg_type_id").append("<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name + " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
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
        $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

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
                $('#spinner00').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 2: When Program is selected   
$("#u_prg_type_id").change(function () {
        var program = $("#u_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#u_fac_id').css({'display':'none'});
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

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
                $('#u_fac_id').css({'display':'block'}); // Show school section

                // Clear existing school options
                $("#u_fac_id").empty();
                $("#u_fac_id").append("<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
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
    $("#u_fac_id").change(function () {
        var school = $("#u_fac_id").val();
        var program = $("#u_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id:program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#u_dept_id').css({'display':'none'});
        $('#u_level_id').css({'display':'none'});

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
                $('#u_dept_id').css({'display':'block'}); // Show department section

                // Clear existing department options
                $("#u_dept_id").empty();
                $("#u_dept_id").append("<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //updating 
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
    $("#u_dept_id").change(function () {
        var department = $("#u_dept_id").val();
        var program = $("#u_prg_type_id").val();
        var school = $("#u_fac_id").val();
        
        var formdata = {
            dept_id: department,
            prg_type:program,
            fac_id:school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#u_splz_id').css({'display':'none'});

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
                $('#u_splz_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#u_splz_id").empty();
                $("#u_splz_id").append("<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
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
        $('#spinner03').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function (data) {
                $('#spinner03').fadeOut('fast');
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
                $('#spinner03').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    // Step 5: When Specialization is selected
    $("#u_splz_id").change(function () {
        var department = $("#u_dept_id").val();
        var program = $("#u_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type:program,
            action: "load_levels"
        };

        // Hide level section initially
        $('#u_level_id').css({'display':'none'});

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
                $('#u_level_id').css({'display':'block'}); // Show level section

                // Clear existing level options
                $("#u_level_id").empty();
                $("#u_level_id").append("<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function (index, value) {
                        $("#u_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //update
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

$("#save_feecateg_spec").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Fees/fee_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        $('#save_feecateg')[0].reset();
                        pop_up_success(data.message);
                        $('#fee_table').load(location.href + " #fee_table");
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
$("#update_form_sp").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "/files/Fees/fee_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#fee_table').load(location.href + " #fee_table");
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
                    pop_wrong("Something went wrong !");
                    
                }
             });
          });          
          
          
          $(document).on('click', '.edit_spec', function (e) {
            var data_id = $(this).data('id');
            var getData = {
                id: data_id,
                action: 'view_category'
            };
            $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Fees/fee_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    
                    if (data[7] && data[7].length > 0) {
                        data[7].forEach(row => {
                            $("#tb_fee").val(row.id);
                        });
                    } else {
                        
                    }


                    // console.log(data);
                    // console.log(data[0].id);
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#fee_id").val(data_id);
                    $("#e_name").val(data[0].name);
                    $("#e_amount").val(data[0].amount);
                    
                    
                    var selectElement0 = document.getElementById('e_camp_id');
                    var selectElement1 = document.getElementById('e_prg_type_id');
                    var selectElement2 = document.getElementById('e_fac_id');
                    var selectElement3 = document.getElementById('e_dept_id');
                    var selectElement4 = document.getElementById('e_splz_id');
                    var selectElement5 = document.getElementById('e_level_id');
                    $("#e_prg_type_id").empty();
                    $("#e_fac_id").empty();
                    $("#e_dept_id").empty();
                    $("#e_splz_id").empty();
                    $("#e_level_id").empty();
                    $.each(data[1], function (index, value) {
                            $("#e_camp_id").append("<option value='" + value.camp_id + "'>" + value.camp_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_prg_type_id").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });    
                    $.each(data[3], function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                    $.each(data[4], function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                    $.each(data[5], function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });    
                    $.each(data[6], function (index, value) {
                            $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                      
                    
                    // Set selected values
                    var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].camp_id + '"]');
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type_id + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].splz_id + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].level_id + '"]');
                    
                    selectedOption0.selected = true;
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        $(document).on('click','.del_spec',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_spec'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this category's status! This operation will affect different reports",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Fees/fee_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                       pop_up_success(data.message);
                      $('#fee_table').load(location.href + " #fee_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
            }
           else {
                swal("operation cancelled!!");
            }
        });
        });



});

    function pop_wrong(feedback) {
          iziToast.warning({
    title: 'Wrong',
    message: feedback,
    position: 'topCenter'
  });
    }
    
      function pop_up_success(feedback) {
    iziToast.success({
    title: 'Info:',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>
