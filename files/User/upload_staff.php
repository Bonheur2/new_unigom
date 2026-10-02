                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1>Users</h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">User List</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <form id="save_module"  method="POST">
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                                    <label>Department</label><br>
                                                    <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id">
                                                        <option value=""></option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_department ");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['dept_id']; ?>"><?php echo $progs_faculty['dept_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="spinner0"></span>
                                                </div>
                                            <button type="submit" class="btn btn-primary"  id="btn_down" ><span id="spinner20"></span>&nbsp;<span id="indicator20">Download Csv</span></button>
                                            <div class="form-group col-12 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                            <label>&nbsp;</label>
                                            <input type="file" id="csvFileInput" accept=".csv" class="form-control mb-2">
                                            <button type="button" id="uploadButton" class="btn btn-primary form-control" >Upload CSV</button>
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
    //download
     $('#btn_down').on('click', function() {
        // Get selected values from the form
        
        const Department = $('#dept_id').val();
        
        
        if ( !Department  ) {
            pop_wrong("Please fill all required fields before downloading.");
            return;
        }
        
        // Send data to the backend
        $.ajax({
            url: "/files/Modules/module_controller.php",
            type: "POST",
            data: { 
                action: 'download_form_data1', 
                dept_id: Department
               
            },
            dataType: "JSON",
            success: function(data) {
                if (data.status === 200) {
                    // Prepare CSV content
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += "family_name,first_name,gender,campus,Nassit Number,Department,qualification,academic,Post\n";
                    csvContent += ` , , , , ,${data.dept_id}, , , ,\n`;

                    // Trigger the download
                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Staff_list.csv");
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
    // upload second
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
        formData.append("action", "upload_csv_data1");
        formData.append("csv_data", JSON.stringify(rows)); // Send rows as JSON

        // Disable the button to prevent multiple clicks
        $('#uploadButton').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: "/files/User/upload_controller.php",
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
                // Re-enable the button regardless of success or failure
                $('#uploadButton').prop('disabled', false).text('Upload CSV');
            }
        });
    };
    reader.readAsText(file);
});
 });
</script>

