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
                                    <?php if($role_id == 3){ ?>
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
                                                    <form id="save_feecateg" action="save_feecateg" method="POST">
                                                        <input type="hidden" name="action" value="register_fees">
                                                        <div class="card-body pb-0">
                                                            
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
                                                            <div class="form-group">
                                                                <label>Campus</label>
                                                                <select class="form-control select2" style="width:100%;" name="cump_id" id="cump_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Program Type</label>
                                                                <div class="input-group">
                                                                    <select class="form-control select2" style="width:100%;" name="prg_type" id="prg_type" required>
                                                                    </select>
                                                                     &nbsp;<span id="spinner00"></span> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Program</label>
                                                                <div class="input-group">
                                                                    <select class="form-control select2" style="width:100%;" name="splz_id" id="splz_id" required>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Level</label>
                                                                <div class="input-group">
                                                                <select class="form-control select2" style="width:100%;" name="prg_lvl" id="prg_lvl" required>
                                                                </select>
                                                                <span id="spinnerLvl"></span>
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
                                                            <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" id="downloadButton" class="btn btn-success form-control">Download IDs in Excel</button>
                                                        </div>
                                                        <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <input type="file" id="csvFileInput" accept=".csv" class="form-control mb-2">
                                                        <button type="button" id="uploadButton" class="btn btn-primary form-control">Upload CSV</button>
                                                    </div>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-12 <?php echo $role_id == 3?'col-sm-8 col-lg-8':'' ?>">
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
                                                                <th scope="col">Category name</th>
                                                                <th scope="col">Campus</th>
                                                                <th scope="col">Program Type</th>
                                                                <th scope="col">Program</th>
                                                                <th scope="col">Level</th>
                                                                <th scope="col">Amount</th>
                                                                <?php if($role_id == 3){ ?>
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
    SELECT fa.*, ca.camp_full_name, pa.prg_type_full_name, sp.splz_full_name, lv.level_full_name
    FROM tbl_fee_category fa
    INNER JOIN tbl_campus ca ON fa.campus_id = ca.camp_id
    INNER JOIN tbl_program_type pa ON fa.prg_id = pa.prg_type_id
    INNER JOIN tbl_specialization sp ON fa.splz_id = sp.splz_id
    INNER JOIN tbl_level lv ON fa.level_id = lv.level_id;
";
$cSelect_fee = $conn->prepare($Select_fee);
$cSelect_fee->execute();

foreach ($cSelect_fee as $row_cSelect_fee) {
?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['camp_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['prg_type_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['splz_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['level_full_name']); ?></td>
        <td><?php echo htmlspecialchars($row_cSelect_fee['amount']); ?></td>
        <?php if($role_id == 3){ ?>
                                                                <th>
                                                                    <div class="buttons row">
                                                                    <button type="button" data-id="<?php echo $row_cSelect_fee['id']; ?>" class="btn btn-icon btn-light btn-sm edit"><span id="spinner4_<?php echo $row_cSelect_fee['id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;Edit </button>
                                                                    <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $row_cSelect_fee['id']; ?>" <?php echo $row_cSelect_fee['status']==1?'checked':''; ?>>
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
<form action="update_form" method="POST" id="update_form">
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
                    <input type="hidden" name="action" value="update_fee_spec">
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

                    <div class="form-group">
                                                                <label>Campus</label>
                                                                <select class="form-control select2" style="width:100%;" name="cump_id" id="e_cump_id" required>
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_campus");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['camp_id']; ?>"><?php echo $progs_faculty['camp_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Program Type</label>
                                                                <div class="input-group">
                                                                    <select class="form-control select2" style="width:100%;" name="prg_type" id="e_prg_type" required>
                                                                    </select>
                                                                     &nbsp;<span id="spinner00"></span> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Program</label>
                                                                <div class="input-group">
                                                                    <select class="form-control select2" style="width:100%;" name="splz_id" id="e_splz_id" required>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Level</label>
                                                                <div class="input-group">
                                                                <select class="form-control select2" style="width:100%;" name="prg_lvl" id="e_prg_lvl" required>
                                                                </select>
                                                                <span id="spinnerLvl"></span>
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
        const campusId = $('#cump_id').val();
        const programTypeId = $('#prg_type').val();
        const programId = $('#splz_id').val();
        const levelId = $('#prg_lvl').val();
        const amount = $('#amount').val();
        const name = $('#name').val();
        
        if (!campusId || !programTypeId || !programId || !levelId || !amount || !name) {
            pop_wrong("Please fill all required fields before downloading.");
            return;
        }
        
        // Send data to the backend
        $.ajax({
            url: "/files/Fees/fee_controller.php",
            type: "POST",
            data: { 
                action: 'download_form_data', 
                campus_id: campusId, 
                program_type_id: programTypeId, 
                program_id: programId, 
                level_id: levelId, 
                amount: amount,
                name: name
            },
            dataType: "JSON",
            success: function(data) {
                if (data.status === 200) {
                    // Prepare CSV content
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += "Name,Campus ID,Program Type ID,Program ID,Level ID,Amount\n";
                    csvContent += `${data.name},${data.campus_id},${data.program_type_id},${data.program_id},${data.level_id},${data.amount}\n`;

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
        //load program types
     $("#cump_id").change(function () {
            var campus = $("#cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#prg').css({'display':'none'});
            $('#dept').css({'display':'none'});
            
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#prg').css({'display':'block'});
                    $("#prg_type").empty();
                   if(data.length>0){
                        $("#prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        //load updating program types
     $("#e_cump_id").change(function () {
            var campus = $("#e_cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#e_prg').css({'display':'none'});
            $('#e_dept').css({'display':'none'});
            
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $('#e_prg').css({'display':'block'});
                    $("#e_prg_type").empty();
                   if(data.length>0){
                        $("#e_prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +" ["+value.prg_type_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        //load programs
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                prg_type: p_type,
                action: "load-specs"
            };
            $('#dept').css({'display':'none'});
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                     $('#mode').css({'display':'block'});
                    $("#splz_id").empty();
                   if(data.length>0){
                        $('#appBtn').css({'display':'block'});
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating programs
     $("#e_prg_type").change(function () {
            var p_type = $("#e_prg_type").val();
            var formdata = {
                prg_type: p_type,
                action: "load-specs"
            };
            $('#e_dept').css({'display':'none'});
            $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#e_dept').css({'display':'block'});
                    $("#e_splz_id").empty();
                   if(data.length>0){
                       $('#e_appBtn').css({'display':'block'});
                        $.each(data, function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load level
$("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            // console.log(p_type);
            var formdata = {
                prg_type: p_type,
                action: "load_levelss"
            };
            $('#lvl').hide(); // Hide initially
            $('#spinnerLvl').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
    type: "POST",
    url: "/files/Programs/program_controller.php",
    data: formdata,
    dataType: "JSON",
    success: function (data) {
        $('#spinnerLvl').fadeOut('fast');
        $('#lvl').show(); // Show level dropdown on success
        $("#prg_lvl").empty(); // Clear existing options
        if (data && data.length > 0) {
            $("#prg_lvl").append("<option value=''>Select Level</option>");
            $.each(data, function (index, level) {
                $("#prg_lvl").append("<option value='" + level.level_id + "'>" + level.level_full_name + "</option>");
            });
        } else {
            $("#prg_lvl").append("<option value=''>No Levels Available</option>");
        }
    },
    error: function (xhr, status, error) {
        $('#spinnerLvl').fadeOut('fast');
        console.error("Error: " + xhr.responseText);
        pop_wrong("Something went wrong while loading levels!");
    }
});

        });

// Load updating level for update form
$("#e_prg_type").change(function () {
    var p_type = $("#e_prg_type").val();
    console.log(p_type);
    var formdata = {
        prg_type: p_type,  // Ensure this key matches the backend
        action: "load_levelss" // Update the action if needed
    };
    $('#e_lvl').hide(); // Hide initially
    $('#spinnerLvl').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
    
    $.ajax({
        type: "POST",
        url: "/files/Programs/program_controller.php",
        data: formdata,
        dataType: "JSON",
        success: function (data) {
            console.log(data)
            $('#spinnerLvl').fadeOut('fast');
            $('#e_lvl').show(); // Show level dropdown on success
            $("#e_prg_lvl").empty(); // Clear existing options
            if (data && data.length > 0) {
                $("#e_prg_lvl").append("<option value=''>Select Level</option>");
                $.each(data, function (index, level) {
                    $("#e_prg_lvl").append("<option value='" + level.level_id + "'>" + level.level_full_name + "</option>");
                });
            } else {
                $("#e_prg_lvl").append("<option value=''>No Levels Available</option>");
            }
        },
        error: function (xhr, status, error) {
            $('#spinnerLvl').fadeOut('fast');
            console.error("Error: " + xhr.responseText);
            pop_wrong("Something went wrong while loading levels!");
        }
    });
});
    
    $("#save_feecateg").submit(function(e){
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
    
     $(document).on('click', '.edit', function (e) {
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
        dataType: "json",
        success: function (data) {
            console.log(data);
            $('#spinner4_' + data_id).fadeOut('fast');

            // Set form fields
            $("#fee_id").val(data_id);
            $("#e_name").val(data.category.name);
            $("#e_amount").val(data.category.amount);

            // Clear and populate dropdowns for Program Type, Program, and Level
            $("#e_cump_id").empty();
            $("#e_prg_type").empty();
            $("#e_splz_id").empty();
            $("#e_prg_lvl").empty();
            
            // Populate campus dropdown and select correct option
            $.each(data.campus, function (index, value) {
                $("#e_cump_id").append(
                    $("<option>", { value: value.camp_id, text: value.camp_full_name })
                );
            });
            $("#e_cump_id").val(data.category.camp_id); // Set selected value
            // Populate Program Type dropdown and select correct option
            $.each(data.program_types, function (index, value) {
                $("#e_prg_type").append(
                    $("<option>", { value: value.prg_type_id, text: value.prg_type_full_name })
                );
            });
            $("#e_prg_type").val(data.category.prg_id); // Set selected value

            // Populate Specialization (Program) dropdown and select correct option
            $.each(data.specializations, function (index, value) {
                $("#e_splz_id").append(
                    $("<option>", { value: value.splz_id, text: value.splz_full_name })
                );
            });
            $("#e_splz_id").val(data.category.splz_id); // Set selected value

            // Populate Level dropdown and select correct option
            $.each(data.levels, function (index, value) {
                $("#e_prg_lvl").append(
                    $("<option>", { value: value.level_id, text: value.level_full_name })
                );
            });
            $("#e_prg_lvl").val(data.category.level_id); // Set selected value

            // Set Campus selection
            $("#e_cump_id").val(data.category.campus_id);

            // Show modal
            $('#updateModal').modal('show');
        },
        error: function () {
            $('#spinner4_' + data_id).fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});

//update 
$("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Fees/fee_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#fee_table').load(location.href + " #fee_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          // activate/deactivate fee categ
        $(document).on('click','.del',function () {
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
