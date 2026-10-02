<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="registrar" value="<?php echo $identification; ?>">
            <?php
                // Fetch all campuses
                $campStmt = $conn->prepare("SELECT camp_id, camp_full_name FROM tbl_campus WHERE camp_active = 1");
                $campStmt->execute();
                $campList = $campStmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch academic years
                $acadStmt = $conn->prepare("SELECT acad_cycle_id, acad_year FROM tbl_acad_cycle ORDER BY acad_year DESC");
                $acadStmt->execute();
                $acadList = $acadStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <section class="section">
                <div class="section-header">
                    <h3>Student List</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Student List</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Filter Criteria</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse show" id="mycard-collapse">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Campus -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Campus</label>
                                                <select class="form-control select2" style="width:100%" id="campus">
                                                    <option value="">-- Select Campus --</option>
                                                    <?php foreach($campList as $camp): ?>
                                                        <option value="<?php echo $camp['camp_id']; ?>"><?php echo $camp['camp_full_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <!-- Program Type -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Program Type</label>
                                                <select class="form-control select2" style="width:100%" id="prg_type">
                                                    <option value="">-- Select Program Type --</option>
                                                </select>
                                            </div>
                                            <!-- Faculty -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Faculty</label>
                                                <select class="form-control select2" style="width:100%" id="faculty">
                                                    <option value="">-- Select Faculty --</option>
                                                </select>
                                            </div>
                                            <!-- Department -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Department</label>
                                                <select class="form-control select2" style="width:100%" id="department">
                                                    <option value="">-- Select Department --</option>
                                                </select>
                                            </div>
                                            <!-- Specialization -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Specialization</label>
                                                <select class="form-control select2" style="width:100%" id="specialization">
                                                    <option value="">-- Select Specialization --</option>
                                                </select>
                                            </div>
                                            <!-- Level -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Level</label>
                                                <select class="form-control select2" style="width:100%" id="level">
                                                    <option value="">-- Select Level --</option>
                                                </select>
                                            </div>
                                            <!-- Academic Year -->
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>Academic Year</label>
                                                <select class="form-control select2" style="width:100%" id="acadYear">
                                                    <option value="">-- All Academic Years --</option>
                                                    <?php foreach($acadList as $ac): ?>
                                                        <option value="<?php echo $ac['acad_cycle_id']; ?>"><?php echo $ac['acad_year']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <button class="btn btn-primary" id="loadStudents"><i class="fas fa-search"></i> Load Students</button>
                                                <a class="btn btn-danger" id="downloadPdf" style="display:none;" target="_blank"><i class="fas fa-file-pdf"></i> Download PDF</a>
                                                <button class="btn btn-success" id="downloadExcel" style="display:none;"><i class="fas fa-file-excel"></i> Download Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Student List Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Student List</h4>
                                </div>
                                <div class="card-body">
                                    <div id="studentListSpinner" class="text-center" style="display:none;">
                                        <i class="fas fa-spinner fa-spin fa-2x"></i> Loading...
                                    </div>
                                    <div class="table-responsive" id="studentTableWrapper" style="display:none;">
                                        <table class="table table-striped table-hover" id="studentTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Full Name</th>
                                                    <th>Gender</th>
                                                    <th>Specialization</th>
                                                    <th>Level</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentBody">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="noStudentMsg" class="text-center text-muted" style="display:none;">
                                        <p>No students found for the selected criteria.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    var controllerUrl = '../files/Programs/program_controller.php';
    var loadedStudents = [];
    var selectedSpecName = '';
    var selectedLevelName = '';

    // Campus → Load Program Types
    $('#campus').on('change', function(){
        var campusId = $(this).val();
        $('#prg_type').html('<option value="">-- Select Program Type --</option>');
        $('#faculty').html('<option value="">-- Select Faculty --</option>');
        $('#department').html('<option value="">-- Select Department --</option>');
        $('#specialization').html('<option value="">-- Select Specialization --</option>');
        $('#level').html('<option value="">-- Select Level --</option>');
        if(campusId){
            $.post(controllerUrl, {action:'load_program_types_by_campus', campus_id: campusId}, function(data){
                $.each(data, function(i, item){
                    $('#prg_type').append('<option value="'+item.prg_type_id+'">'+item.prg_type_full_name+'</option>');
                });
            },'json');
        }
    });

    // Program Type → Load Faculties & Levels
    $('#prg_type').on('change', function(){
        var typeId = $(this).val();
        $('#faculty').html('<option value="">-- Select Faculty --</option>');
        $('#department').html('<option value="">-- Select Department --</option>');
        $('#specialization').html('<option value="">-- Select Specialization --</option>');
        $('#level').html('<option value="">-- Select Level --</option>');
        if(typeId){
            $.post(controllerUrl, {action:'load_faculties', type: typeId}, function(data){
                $.each(data, function(i, item){
                    $('#faculty').append('<option value="'+item.fac_id+'">'+item.fac_full_name+'</option>');
                });
            },'json');
            $.post(controllerUrl, {action:'load_levels', type: typeId}, function(data){
                $.each(data, function(i, item){
                    $('#level').append('<option value="'+item.level_id+'">'+item.level_full_name+'</option>');
                });
            },'json');
        }
    });

    // Faculty → Load Departments
    $('#faculty').on('change', function(){
        var facId = $(this).val();
        var typeId = $('#prg_type').val();
        $('#department').html('<option value="">-- Select Department --</option>');
        $('#specialization').html('<option value="">-- Select Specialization --</option>');
        if(facId){
            $.post(controllerUrl, {action:'load_departments_by_faculty', fac_id: facId, prg_type: typeId}, function(data){
                $.each(data, function(i, item){
                    $('#department').append('<option value="'+item.dept_id+'">'+item.dept_full_name+'</option>');
                });
            },'json');
        }
    });

    // Department → Load Specializations
    $('#department').on('change', function(){
        var deptId = $(this).val();
        $('#specialization').html('<option value="">-- Select Specialization --</option>');
        if(deptId){
            $.post(controllerUrl, {action:'load_specs_by_dept', dept_id: deptId}, function(data){
                $.each(data, function(i, item){
                    $('#specialization').append('<option value="'+item.splz_id+'">'+item.splz_full_name+'</option>');
                });
            },'json');
        }
    });

    // Load Students
    $('#loadStudents').on('click', function(){
        var specId = $('#specialization').val();
        var levelId = $('#level').val();
        var acadYearId = $('#acadYear').val();
        if(!specId || !levelId){
            swal("Warning", "Please select Specialization and Level", "warning");
            return;
        }
        $('#studentListSpinner').show();
        $('#studentTableWrapper').hide();
        $('#noStudentMsg').hide();
        $('#studentBody').html('');
        loadedStudents = [];
        selectedSpecName = $('#specialization option:selected').text();
        selectedLevelName = $('#level option:selected').text();

        $.post(controllerUrl, {action:'load_student_list', splz_id: specId, level_id: levelId, acad_cycle_id: acadYearId}, function(data){
            $('#studentListSpinner').hide();
            if(data.length > 0){
                loadedStudents = data;
                $.each(data, function(i, stu){
                    var status = (stu.reg_active == 1) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>';
                    $('#studentBody').append(
                        '<tr>'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+stu.reg_no+'</td>'+
                            '<td>'+stu.fname+' '+stu.lname+'</td>'+
                            '<td>'+((stu.gender=='M')?'Male':'Female')+'</td>'+
                            '<td>'+stu.splz_full_name+'</td>'+
                            '<td>'+stu.level_full_name+'</td>'+
                            '<td>'+status+'</td>'+
                        '</tr>'
                    );
                });
                $('#studentTableWrapper').show();
                $('#downloadPdf').attr('href', '../files/Student/download_student_list?splz_id='+specId+'&level_id='+levelId+'&acad_cycle_id='+acadYearId).show();
                $('#downloadExcel').show();
            } else {
                $('#noStudentMsg').show();
                $('#downloadPdf').hide();
                $('#downloadExcel').hide();
            }
        },'json');
    });

    // Download Excel
    $('#downloadExcel').on('click', function(){
        if(loadedStudents.length === 0) return;

        var campus = $('#campus option:selected').text();
        var prgType = $('#prg_type option:selected').text();
        var faculty = $('#faculty option:selected').text();
        var department = $('#department option:selected').text();

        var headerRows = [
            ['STUDENT LIST'],
            [''],
            ['Campus:', campus],
            ['Program Type:', prgType],
            ['Faculty:', faculty],
            ['Department:', department],
            ['Specialization:', selectedSpecName],
            ['Level:', selectedLevelName],
            ['Academic Year:', $('#acadYear option:selected').text() || 'All'],
            ['Total Students:', loadedStudents.length],
            ['Generated on:', new Date().toLocaleString()],
            ['']
        ];

        var tableHeader = ['#', 'Student ID', 'Full Name', 'Gender', 'Specialization', 'Level'];

        var tableData = [];
        $.each(loadedStudents, function(i, stu){
            tableData.push([
                i + 1,
                stu.reg_no,
                stu.fname + ' ' + stu.lname,
                (stu.gender == 'M') ? 'Male' : 'Female',
                stu.splz_full_name,
                stu.level_full_name
            ]);
        });

        var allRows = headerRows.concat([tableHeader]).concat(tableData);
        var ws = XLSX.utils.aoa_to_sheet(allRows);

        ws['!cols'] = [
            {wch: 5},
            {wch: 20},
            {wch: 35},
            {wch: 10},
            {wch: 35},
            {wch: 20}
        ];

        ws['!merges'] = [
            {s:{r:0,c:0}, e:{r:0,c:5}}
        ];

        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Student List');
        XLSX.writeFile(wb, 'Student_List_' + selectedSpecName.replace(/\s+/g, '_') + '.xlsx');
    });

});
</script>
