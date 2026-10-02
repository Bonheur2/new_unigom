<!-- Start app main Content -->
<?php
        $conn->exec("TRUNCATE TABLE  tbl_modules_live");
?>
        <div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
                    <section class="section">
                        <div class="section-header">
                            <h3>Modules by Level</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Module to year (V2)</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>New Settings</h4>
                                            <div class="card-header-action">
                                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="collapse hide" id="mycard-collapse">
                                            <div class="card-body col-12">
                                                <form id="save_assign2" action="save_assign2" method="POST">
                                                    <input type="hidden" name="action" value="assign2">
                                                    <input type="hidden" name="camp_id" value="<?php echo $camp_id; ?>">
                                                    <div class="card-body pb-0 row">
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Program Type</label><br>
                                                            <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" required>
                                                                <option></option>
                                                                <?php
                                                                    $sql_prg=$conn->prepare("SELECT 
                                                                                    tbl_program_type.*,
                                                                                    tbl_campus.camp_full_name 
                                                                                        FROM tbl_program_type 
                                                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                                        ORDER BY tbl_program_type.status ASC");
                                                                    $sql_prg->execute();
                                                                    $i=1;
                                                                    while($progs_faculty=$sql_prg->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner1"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="fac" hidden>
                                                            <label>Faculty</label><br>
                                                            <select class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                                                            </select>
                                                            <span id="spinner2"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="dept" hidden>
                                                            <label>Department</label><br>
                                                            <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id"required>
                                                            </select>
                                                            <span id="spinner3"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                                            <label>Specialization</label><br>
                                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                            </select>
                                                            <span id="spinner5"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="lev" hidden>
                                                            <label>Level</label><br>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="term" hidden>
                                                            <label>Semester </label><br>
                                                            <select class="form-control select2" style="width:100%" name="term_id" id="term_id" required>
                                                                <option selected disabled>Select Semester</option>
                                                            <?php
                                                            $select_semister="SELECT * FROM semester";
                                                            $cselect_semister=$conn->prepare($select_semister);
                                                            $cselect_semister->execute();
                                                            foreach($cselect_semister as $row_cselect_semister){
                                                                        
                                                            ?>
                                                            <option value="<?php echo $row_cselect_semister['id']?>"><?php echo $row_cselect_semister['name']?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="mod" hidden>
                                                            <label>Module</label><br>
                                                            <select class="form-control select2" style="width:100%" name="mod_id[]" id="mod_id" required multiple>
                                                            </select>
                                                            <span id="spinner6"></span>
                                                        </div>
                                                        
                                                          <div class="card-body col-12 modules_id" hidden>
                                                     <table class="table">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                 <th scope="col">Module Name</th>
                                                                <th scope="col">Credited Module</th>
                                                                <th scope="col">Module credits</th>
                                                                <th scope="col">Credit price</th>
                                                                <th scope="col">Marks (CAT)</th>
                                                                <th scope="col">Marks (Exam)</th>
                                                                <th scope="col">Has project</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="getData">
                                                           </tbody>
                                                        </table>
                                                        
                                                        </div>
                                                        
                                                        <!--<div class="form-group  col-12 col-sm-4 col-lg-4" id="cm" hidden>-->
                                                        <!--    <label>Credited Module</label><br>-->
                                                        <!--    <select class="form-control select2" style="width:100%;" name="credited_module" id="credited_module" required>-->
                                                        <!--        <option value="1">Yes</option>-->
                                                        <!--        <option value="0">No</option>-->
                                                        <!--    </select>-->
                                                        <!--</div>-->
                                                        <!--<div class="form-group  col-12 col-sm-4 col-lg-4" id="mc" hidden>-->
                                                        <!--    <label>Module credits</label><br>-->
                                                        <!--    <input type="number" class="form-control" name="module_credits" id="module_credits"/>-->
                                                        <!--    <span id="spinner8"></span>-->
                                                        <!--</div>-->
                                                        <!--<div class="form-group  col-12 col-sm-4 col-lg-4" id="cp" hidden>-->
                                                        <!--    <label>Credit price</label><br>-->
                                                        <!--    <input type="number" class="form-control" name="credit_price" id="credit_price"/>-->
                                                        <!--</div>-->
                                                        <!--<div class="form-group  col-12 col-sm-4 col-lg-4" id="marks" hidden>-->
                                                        <!--    <label>Marks (CAT | Exam)</label>-->
                                                        <!--    <div style="display:flex;flex-direction:row;">-->
                                                        <!--        <div class="form-check" style="margin-right:10px;">-->
                                                        <!--            <input type="number" class="form-control" name="cat" placeholder="CAT" required>-->
                                                        <!--        </div>-->
                                                        <!--        <div class="form-check">-->
                                                        <!--            <input type="number" class="form-control" name="exam" placeholder="Exam" required>-->
                                                        <!--        </div>-->
                                                        <!--    </div>-->
                                                        <!--</div>-->
                                                        <!--<div class="form-group  col-12 col-sm-4 col-lg-4" id="project" hidden>-->
                                                        <!--    <label>Has project?</label><br>-->
                                                        <!--    <div style="display:flex;flex-direction:row;">-->
                                                        <!--        <div class="form-check" style="margin-right:10px;">-->
                                                        <!--            <input type="radio" class="form-check-input" value="yes" name="is_project" id="is_project" required>-->
                                                        <!--            <label class="form-check-label" for="exampleRadios1">Yes</label>-->
                                                        <!--        </div>-->
                                                        <!--        <div class="form-check">-->
                                                        <!--            <input type="radio" class="form-check-input" value="no" name="is_project" id="is_project" required>-->
                                                        <!--            <label class="form-check-label" for="exampleRadios1">No</label> -->
                                                        <!--        </div>-->
                                                        <!--    </div>-->
                                                        <!--</div>    -->
                                                        <div class="form-group  col-12 col-sm-12 col-lg-12">
                                                            <button type="submit" class="btn btn-primary"  id="btn" hidden><span id="spinner20"></span>&nbsp;<span id="indicator20">Save</span></button>
                                                            <button type="submit" class="btn btn-primary"  id="btn_down" hidden><span id="spinner20"></span>&nbsp;<span id="indicator20">Download Csv</span></button>
                                                            &nbsp;<button type="button" class="btn btn-icon btn-primary btn-sm upload"  id="up" hidden><span id="spinner-6"></span>&nbsp;<i class="fas fa-upload"></i>&nbsp;<span id="indicator-6">Upload CSV</span>&nbsp;</button>
                                                        </div>
                                            <!--<div class="form-group col-12 col-sm-6 col-lg-4">-->
                                            <!--<div class="form-group">-->
                                            <!--<label>&nbsp;</label>-->
                                            <!--<input type="file" id="csvFileInput" accept=".csv" class="form-control mb-2">-->
                                            <!--<button type="button" id="uploadButton" class="btn btn-primary form-control" hidden>Upload CSV</button>-->
                                            <!--</div>-->
                                            <!--</div>-->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card" id="sample-login">
                                    <div class="card-header">
                                        <div class="col-9 col-md-10">
                                            <h4>Registered Modules</h4>
                                        </div>
                                        
                                        <div class="col-3 col-md-2">
                                            <a  class="btn btn-icon btn-success btn-sm" href="edu?mis=mod_assign_verify"><i class="fas fa-clipboard"></i>&nbsp;Review</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="module_to_year_table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Module Code</th>
                                                    <th scope="col">Module Name</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Level</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $sql=$conn->prepare("SELECT modules.module_code,
                                                                        modules.module_name,
                                                                        tbl_program_type.prg_type_full_name,
                                                                        tbl_faculty.fac_full_name,
                                                                        tbl_department.dept_full_name,
                                                                        tbl_level.level_full_name,
                                                                        tbl_modules.*
                                                                        FROM tbl_modules 
                                                                        INNER JOIN tbl_program_type ON 
                                                                        tbl_modules.prg_type=tbl_program_type.prg_type_id
                                                                        INNER JOIN tbl_faculty ON 
                                                                        tbl_modules.fac_id=tbl_faculty.fac_id
                                                                        INNER JOIN tbl_department ON 
                                                                        tbl_modules.dept_id=tbl_department.dept_id
                                                                        INNER JOIN modules ON 
                                                                        modules.module_id=tbl_modules.mod_id
                                                                        INNER JOIN tbl_level ON 
                                                                        tbl_modules.level_id=tbl_level.level_id
                                                                        WHERE tbl_level.status=1 ORDER BY tbl_modules.status ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($mods=$sql->fetch()){
                                                 ?>
                                                <tr>
                                                    <th scope="row"><?php echo $i++; ?></th>
                                                    <td><?php echo $mods['module_code']; ?></td>
                                                    <td><?php echo $mods['module_name']; ?></td>
                                                    <td><?php echo $mods['dept_full_name']; ?></td>
                                                    <td><?php echo $mods['level_full_name']; ?></td>
                                                    <th>  
                                                        <div class="buttons row">
                                                            <button type="button" data-id="<?php echo $mods['module_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $mods['module_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>
                                                            <label class="custom-switch btn btn-sm btn-light">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $mods['module_id']; ?>" <?php echo $mods['status']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $mods['module_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-icon btn-secondary btn-sm export"><span id="spinner-5"></span>&nbsp;<i class="fas fa-download"></i>&nbsp;<span id="indicator-5">Export CSV</span>&nbsp;</button>&nbsp;&nbsp;
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--update modal-->
            <form action="update_form" method="POST" id="update_form">
                <div class="modal fade" role="dialog" id="updateModal">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><span id="m_name"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body row">
                                <input type="hidden" name="module_id" id="module_id">
                                <input type="hidden" name="action" value="update_assign">
                                    <div class="form-group  col-12 col-sm-6 col-lg-6">
                                        <label>Program Type</label><br>
                                        <select class="form-control select2" style="width:100%" name="prg_type" id="e_prg_type">
                                            <?php
                                                $sql_prg=$conn->prepare("SELECT 
                                                                tbl_program_type.*,
                                                                tbl_campus.camp_full_name 
                                                                    FROM tbl_program_type 
                                                                INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                                    ORDER BY tbl_program_type.status ASC");
                                                $sql_prg->execute();
                                                $i=1;
                                                while($progs_faculty=$sql_prg->fetch()){
                                                    ?>
                                            <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                        <span id="spinner-1"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_fac">
                                        <label>Faculty</label><br>
                                        <select class="form-control select2" style="width:100%" name="fac_id" id="e_fac_id" required>
                                        </select>
                                        <span id="spinner-2"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_dept">
                                        <label>Department</label><br>
                                        <select class="form-control select2" style="width:100%" name="dept_id" id="e_dept_id"required>
                                        </select>
                                        <span id="spinner-3"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_spec">
                                        <label>Specialization</label><br>
                                        <select class="form-control select2" style="width:100%" name="splz_id" id="e_splz_id">
                                        </select>
                                        <span id="spinner-5"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_lev">
                                        <label>Level</label><br>
                                        <select class="form-control select2" style="width:100%" name="level_id" id="e_level_id" required>
                                        </select>
                                        <span id="spinner-4"></span>
                                    </div>

                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_mod">
                                        <label>Module</label><br>
                                        <select class="form-control select2" style="width:100%" name="mod_id" id="e_mod_id" required>
                                        </select>
                                        <span id="spinner-6"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_cm">
                                        <label>Credited Module</label><br>
                                        <select class="form-control select2" style="width:100%;" name="credited_module" id="e_credited_module" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_mc">
                                        <label>Module credits</label><br>
                                        <input type="number" class="form-control" name="module_credits" id="e_module_credits"/>
                                        &nbsp;<span id="spinner-8"></span>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_cp">
                                        <label>Credit price</label><br>
                                        <input type="number" class="form-control" name="credit_price" id="e_credit_price"/>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_term">
                                        <label>Semester</label><br>
                                        <select class="form-control select2" style="width:100%" name="term_id" id="e_term_id" required>
                                            <!--<option selected disabled>Select Semester</option>-->
                                                            <?php
                                                            $select_semister="SELECT * FROM semester";
                                                            $cselect_semister=$conn->prepare($select_semister);
                                                            $cselect_semister->execute();
                                                            foreach($cselect_semister as $row_cselect_semister){
                                                                        
                                                            ?>
                                                            <option value="<?php echo $row_cselect_semister['id']?>"><?php echo $row_cselect_semister['name']?></option>
                                                            <?php
                                                            }
                                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6">
                                        <label>Marks (CAT | Exam)</label>
                                        <div style="display:flex;flex-direction:row;">
                                            <div class="form-check" style="margin-right:10px;">
                                                <input type="number" class="form-control" name="cat" id="e_cat" placeholder="CAT" required>
                                            </div>
                                            <div class="form-check">
                                                <input type="number" class="form-control" name="exam" id="e_exam" placeholder="Exam" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group  col-12 col-sm-6 col-lg-6" id="e_project">
                                        <label>Has project?</label><br>
                                        <div style="display:flex;flex-direction:row;">
                                            <div class="form-check" style="margin-right:10px;">
                                                <input type="radio" class="form-check-input" value="yes" name="is_project" id="is_project_yes" required>
                                                <label class="form-check-label" for="exampleRadios1">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" value="no" name="is_project" id="is_project_no" required>
                                                <label class="form-check-label" for="exampleRadios1">No</label> 
                                            </div>
                                        </div>
                                    </div>  
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="ubtn"><span id="spinner200"></span>&nbsp;<span id="indicator200">Save changes</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
               <!--end update modal-->
               
            <!--upload modal-->
            <form action="upload_form" id="upload_form" method="POST" enctype="multipart/form-data">
                <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Upload CSV file <?php echo $camp_id; ?></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body pb-0 row">
                                    <div class="form-group">
                                        <input type="hidden" name="action" value="upload_mo_level">
                                        <input type="hidden" name="camp" value="<?php echo $camp_id; ?>">
                                        <input type="file" id="csvFileInput" accept=".csv" class="form-control mb-2">
                                    </div>
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="uploadButton" class="btn btn-primary"><span id="spinner201"></span>&nbsp;<span id="indicator201">Upload</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
               
    </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
        $('#btn_down').on('click', function() {
    // Get selected values from the form
    const Program = $('#prg_type').val();
    const School = $('#fac_id').val();
    const Department = $('#dept_id').val();
    const Specialization = $('#splz_id').val();
    const Level = $('#level_id').val();
    const Term = $('#term_id').val();

    const SpecializationText = $("#splz_id option:selected").text().trim();
    const LevelText = $("#level_id option:selected").text().trim();

    if (!Program || !School || !Department || !Specialization || !Level || !Term) {
        pop_wrong("Please fill all required fields before downloading.");
        return;
    }

    // Send data to the backend
    $.ajax({
        url: "/files/Modules/module_controller.php",
        type: "POST",
        data: { 
            action: 'download_form_data', 
            prg_type: Program, 
            fac_id: School, 
            dept_id: Department,
            splz_id: Specialization,
            level_id: Level,
            term_id: Term
        },
        dataType: "JSON",
        success: function(data) {
            if (data.status === 200) {
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "COURSE CODE,COURSE DESCRIPTION,Program,School,Department,Specialization,Level,Term\n";
                csvContent += ` , ,${Program},${School},${Department},${Specialization},${Level},${Term}\n`;

                // Generate dynamic file name: Specialization_Level.csv
                const fileName = `Modules_${SpecializationText.replace(/\s+/g, "_")}_${LevelText.replace(/\s+/g, "_")}.csv`;

                // Trigger the download
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", fileName);
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


    //upload
   $('#uploadButton').on('click', function() {
    const fileInput = document.getElementById('csvFileInput');
    const file = fileInput.files[0];
    
    // Get selected form values
    const Program = $('#prg_type').val();
    const School = $('#fac_id').val();
    const Department = $('#dept_id').val();
    const Specialization = $('#splz_id').val();
    const Level = $('#level_id').val();
    const Term = $('#term_id').val();

    // Validate inputs
    if (!Program || !School || !Department || !Specialization || !Level || !Term) {
        pop_wrong("Please fill all required fields before downloading.");
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
        
        formData.append("prg_type", Program);
        formData.append("fac_id", School);
        formData.append("dept_id", Department);
        formData.append("splz_id", Specialization);
        formData.append("level_id", Level);
        formData.append("term_id", Term);

        // Disable the button to prevent multiple clicks
        $('#uploadButton').prop('disabled', true).text('Uploading...');

        $.ajax({
            url: "/files/Modules/module_controller.php",
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
    

    $('#module_to_year_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
        
     $('.upload').click(function () {
        $("#uploadModal").modal('show');
     });
//load faculties
     $("#prg_type").change(function () {
            var formdata = {
                type: $("#prg_type").val(),
                action: "load_faculties"
            };
           
             $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#fac").attr("hidden", true);
            $("#fac_id").empty();
            $("#dept").attr("hidden", true);
            $("#dept_id").empty();
            $("#spec").attr("hidden", true);
            $("#splz_id").empty();
            $("#lev").attr("hidden", true);
            $("#level_id").empty();
            $("#mod").attr("hidden", true);
            $("#mod_id").empty();

            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $("#fac").removeAttr("hidden");
                    $("#fac_id").empty();
                   if(data.length>0){
                        $("#fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load updating faculties
     $("#e_prg_type").change(function () {
            var formdata = {
                type: $("#e_prg_type").val(),
                action: "load_faculties"
            };
             $('#spinner-1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#e_fac").attr("hidden", true);
            $("#e_fac_id").empty();
            
            $("#e_dept").attr("hidden", true);
            $("#e_dept_id").empty();
            
            $("#e_spec").attr("hidden", true);
            $("#e_splz_id").empty();
            
            $("#e_lev").attr("hidden", true);
            $("#e_level_id").empty();
            
            $("#e_mod").attr("hidden", true);
            $("#e_mod_id").empty();
            
            $("#ubtn").attr('disabled',true);
            
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-1').fadeOut('fast');
                    $("#e_fac").removeAttr("hidden");
                    $("#e_fac_id").empty();
                   if(data.length>0){
                        $("#e_fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load departments
     $("#fac_id").change(function () {
            var formdata = {
                fac: $("#fac_id").val(),
                action: "load_departments"
            };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#dept").attr("hidden", true);
            $("#dept_id").empty();
            $("#spec").attr("hidden", true);
            $("#splz_id").empty();
            $("#mod").attr("hidden", true);
            $("#mod_id").empty();
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner2').fadeOut('fast');
                    $("#dept").removeAttr("hidden");
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner2').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load updating departments
     $("#e_fac_id").change(function () {
            var formdata = {
                fac: $("#e_fac_id").val(),
                action: "load_departments"
            };
            $('#spinner-2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#e_dept").attr("hidden", true);
            $("#e_dept_id").empty();
            $("#e_spec").attr("hidden", true);
            $("#e_splz_id").empty();
            $("#e_mod").attr("hidden", true);
            $("#e_mod_id").empty();
            $("#ubtn").attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-2').fadeOut('fast');
                    $("#e_dept").removeAttr("hidden");
                    $("#e_dept_id").empty();
                   if(data.length>0){
                        $("#e_dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner2').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load levels
     $("#prg_type").change(function () {
            var formdata = {
                type: $("#prg_type").val(),
                action: "load_levels"
            };
             $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner1').fadeOut('fast');
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load updating levels
     $("#e_prg_type").change(function () {
            var formdata = {
                type: $("#e_prg_type").val(),
                action: "load_levels"
            };
             $('#spinner-1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-1').fadeOut('fast');
                    $("#e_level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-1').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
//load specs
     $("#dept_id").change(function () {
            var formdata = {
                department: $("#dept_id").val(),
                action: "load_specs"
            };
            $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#spec").attr("hidden", true);
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner3').fadeOut('fast');
                    $("#splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                   $("#lev").removeAttr("hidden");
                   $("#term").removeAttr('hidden');
                   $("#up").removeAttr('hidden');
                   $("#spec").removeAttr("hidden");
                 },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//load updating specs
     $("#e_dept_id").change(function () {
            var formdata = {
                department: $("#e_dept_id").val(),
                action: "load_specs"
            };
            $('#spinner-3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $("#e_spec").attr("hidden", true);
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-3').fadeOut('fast');
                    $("#e_splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                   $("#e_lev").removeAttr("hidden");
                   $("#e_spec").removeAttr("hidden");
                 },
                error:function(error){
                    $('#spinner-3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });

//load modules
     $("#dept_id").change(function () {
            var formdata = {
                type: $("#prg_type").val(),
                dept_id: $("#dept_id").val(),
                action: "load_modules"
            };
            $("#mod").attr("hidden", true);
            $("#mod_id").empty();
             $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner3').fadeOut('fast');
                    $("#mod").removeAttr('hidden');
                    $("#mod_id").empty();
                   if(data.length>0){
                           $("#mod_id").append("<option disabled selected>--choose modules--</option>");
                        $.each(data, function (index, value) {
                            $("#mod_id").append("<option value='" + value.module_id + "'>" + value.module_name +" ["+value.module_code+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });

//load updating modules
     $("#e_dept_id").change(function () {
            var formdata = {
                type: $("#e_prg_type").val(),
                dept_id: $("#e_dept_id").val(),
                action: "load_modules"
            };
            $("#e_mod").attr("hidden", true);
             $('#spinner-4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-4').fadeOut('fast');
                    $("#e_mod").removeAttr('hidden');
                    $("#e_mod_id").empty();
                   if(data.length>0){
                       $("#ubtn").removeAttr('disabled'); 
                        $.each(data, function (index, value) {
                            $("#e_mod_id").append("<option value='" + value.module_id + "'>" + value.module_name +" ["+value.module_code+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-4').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
                 
            });
        });
        
//show inputs
     $("#mod_id").change(function () {
        
        if($("#mod_id").val()!=''){
            $("#cp").removeAttr('hidden');
            $("#cm").removeAttr('hidden');
            $("#mc").removeAttr('hidden');
            $("#marks").removeAttr("hidden");
            $("#project").removeAttr('hidden');      
            $("#btn").removeAttr('hidden');
            $("#btn_down").removeAttr('hidden');
            $("#uploadButton").removeAttr('hidden');
            
         }
        else{
            $("#cp").attr('hidden',true);
            $("#cm").attr('hidden',true);
            $("#marks").attr("hidden", true);
            $("#mc").attr('hidden',true);
            $("#project").attr('hidden',true);      
            $("#btn").attr('hidden',true);
            $("#btn_down").attr('hidden',true);
            $("#uploadButton").attr('hidden',true);
        }
       var selectedValues = $(this).val();
         if (selectedValues) {
        $.each(selectedValues, function (index, value) {
           
            var formData = {
                mod_id: value,
                prg_type: $("#prg_type").val(),
                fac_id: $("#fac_id").val(),
                dept_id: $("#dept_id").val(),
                splz_id: $("#splz_id").val(),
                level_id: $("#level_id").val(),
                action: "save_data_live"
            };
          $.ajax({
                url: "/files/Modules/module_controller_live.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                 if(data.status==403){
                     console.log(data.message);
                 }   
                 if(data.status==200){
                     console.log(data.message); 
                 }
                 getData();  
                },error: function(){
                    pop_wrong("Something went wrong!");
                    
                }
             });
        });
      }
      function getData(){
        var formData={
           action:"get_data_live"
          }
          $.ajax({
                url: "/files/Modules/module_controller_live.php",
                type: "POST",
                data: formData,
                success: function(data){
                 $(".modules_id").attr('hidden',false);  
                 $("#getData").html(data);  
                },error: function(){
                    pop_wrong("Something went wrong!");
                    
                }
             });  
      }
        });

    //save settings
    $("#save_assign2").submit(function(e){
            e.preventDefault();
         var formData = new FormData(this);
            $('#spinner20').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator20').html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller_live.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Save");
                    if(data.status==200){
                        $(".modules_id").attr('hidden',true);  
                        $('#save_assign2')[0].reset();
                        $('#module_to_year_table').load(location.href + " #module_to_year_table");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner20').fadeOut('fast');
                    $('#indicator20').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
// delete module
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete_assign'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this module status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                    else if(data.status==200){
                        pop_up_success(data.message);
                       $('#module_table').load(location.href + " #module_table");
                    }
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
            }
           else {
                swal("operation Cancelled!!");
            }
        });
        });
//pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_assign'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $('#updateModal').modal('show');
                    $("#module_id").val(data_id);

                    var selectElement1 = document.getElementById('e_prg_type');
                    var selectElement2 = document.getElementById('e_fac_id');
                    var selectElement3 = document.getElementById('e_dept_id');
                    var selectElement4 = document.getElementById('e_splz_id');
                    var selectElement5 = document.getElementById('e_level_id');
                    var selectElement6 = document.getElementById('e_mod_id');
                    var selectElement7 = document.getElementById('e_credited_module');
                    var selectElement8 = document.getElementById('e_term_id');
                    
                    $("#e_fac_id").empty();
                    $("#e_dept_id").empty();
                    $("#e_splz_id").empty();
                    $("#e_level_id").empty();
                    $("#e_mod_id").empty();
                    $.each(data[1], function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" [ "+value.fac_short_name+" ]</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" [ "+value.dept_short_name+" ]</option>");
                        });
                    $.each(data[3], function (index, value) {
                            $("#e_splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" [ "+value.splz_short_name+" ]</option>");
                        });
                    $.each(data[4], function (index, value) {
                            $("#e_level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    $.each(data[5], function (index, value) {
                            $("#e_mod_id").append("<option value='" + value.module_id + "'>" + value.module_name +" [ "+value.module_code+" ]</option>");
                        });
                    // // Set selected values
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].splz_id + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].level_id + '"]');
                    var selectedOption6 = selectElement6.querySelector('option[value="' + data[0].mod_id + '"]');
                    var selectedOption7 = selectElement7.querySelector('option[value="' + data[0].credited_module + '"]');
                    var selectedOption8 = selectElement8.querySelector('option[value="' + data[0].term_id + '"]');
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    selectedOption6.selected = true;
                    selectedOption7.selected = true;
                    selectedOption8.selected = true;
                    
                    selectElement1.prepend(selectedOption1);
                    selectElement2.prepend(selectedOption2);
                    selectElement3.prepend(selectedOption3);
                    selectElement4.prepend(selectedOption4);
                    selectElement5.prepend(selectedOption5);
                    selectElement6.prepend(selectedOption6);
                    selectElement7.prepend(selectedOption7);
                    selectElement8.prepend(selectedOption8);
                    
                    $("#e_module_credits").val(data[0].module_credits);
                    $("#e_credit_price").val(data[0].credit_price);
                    if(data[0].is_project=='yes'){
                        $("#is_project_yes").attr('checked',true);
                    }
                    else{
                        $("#is_project_no").attr('checked',true);
                    }
                    $("#e_cat").val(data[0].cat);
                    $("#e_exam").val(data[0].exam);
                    var Year = selectedOption5.textContent;
                    var Module = selectedOption6.textContent;
                    $("#m_name").html(Module+" | "+Year);
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    //update settings
    $("#update_form").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner200').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator200').html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner200').fadeOut('fast');
                    $('#indicator200').html("Save changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        $('#module_to_year_table').load(location.href + " #module_to_year_table");
                        pop_up_success(data.message);
                        
                        $("#updateModal").modal('hide');
                    }
                    if(data.status==401){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner200').fadeOut('fast');
                    $('#indicator200').html("Save changes");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
    //export modules
    $('.export').click(function () {
        var formData = {
            camp: $("#camp_id").val(),
            action:'export_modules'
            }
        $('#spinner-5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator-5').html("Exporting...");
        
        $.ajax({
            url: "/files/Modules/module_controller.php",
            type: "POST",
            data: formData,
            success: function(data){
                $('#spinner-5').fadeOut('fast');
                $('#indicator-5').html("Export");
                window.location.href = '/files/Modules/modules_by_level.csv';
                pop_up_success("Modules exported successfully!");
            },
            error: function(){
                $('#spinner-5').fadeOut('fast');
                $('#indicator-5').html("Export");
                pop_wrong("Something went wrong!");
            }
        });

    });

//upload modules
$("#upload_form").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this);
    formData.append('prg_type', $('#prg_type').val());
    formData.append('fac_id', $('#fac_id').val());
    formData.append('dept_id', $('#dept_id').val());
    formData.append('splz_id', $('#splz_id').val());
    formData.append('level_id', $('#level_id').val());
    formData.append('sem', $('#term_id').val());
    
    $('#spinner201').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $('#indicator201').html("Saving...");
    
    $.ajax({
        url: "/files/Modules/module_controller.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            $('#spinner201').fadeOut('fast');
            $('#indicator201').html("Save");
            if(data == 200){
                $('#upload_form')[0].reset();
                $("#uploadModal").modal('hide');
                pop_up_success("modules uploaded successfully!");
                $('#module_to_year_table').load(location.href + " #module_to_year_table");
            }
            if(data == 401){
                pop_wrong("Some modules are no inserted!");
            }
            if(data == 402){
                pop_wrong("Some modules already exists!");
            }
            if(data == 500){
                pop_wrong("Something went wrong!");
            }
        },
        error: function(){
            $('#spinner201').fadeOut('fast');
            $('#indicator201').html("Save");
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