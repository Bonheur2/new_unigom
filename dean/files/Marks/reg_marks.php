<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Marks</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Marks</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Class information</h4>
                                            <div class="card-header-action">
                                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                            </div>
                                        </div>
                                        <div class="collapse show" id="mycard-collapse">
                                            <div class="">
                                                    <div class="card-body row">
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <label>Program Types</label>
                                                            <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type">
                                                                <option disabled selected>--choose one--</option>
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
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                                            <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                            </select>
                                                            <span id="spinner2"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="intake" hidden>
                                                            <label>Intake</label>
                                                            <select class="form-control select2" style="width:100%" name="intake_id" id="intake_id">
                                                                <?php
                                                                    $sql_intakes=$conn->prepare("SELECT i.intake_id as intake_id,i.intake_month as intake_month,a.acad_year, i.acad_cycle_id as acad_cycle_id,
                                                                            			b.prg_type_full_name AS prg_type_full_name,b.prg_type_short_name AS prg_type_short_name 
                                                                            			FROM tbl_intake i,tbl_acad_cycle a,tbl_program_type b 
                                                                            			WHERE i.acad_cycle_id=a.acad_cycle_id AND i.prg_type=b.prg_type_id 
                                                                            			ORDER BY i.intake_id DESC");
                                                                    $sql_intakes->execute();
                                                                    while($intake=$sql_intakes->fetch()){
                                                                        
                                                                        ?>
                                                                <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year'].' [ '.$row['prg_type_short_name'].' ]'; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="level" hidden>
                                                            <label>Level</label>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                                            </select>
                                                            <span id="spinner3"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="mode" hidden>
                                                            <label>Learning mode</label>
                                                            <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
                                                                <option></option>
                                                                <?php
                                                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status=1");
                                                                    $sql_mode->execute();
                                                                    while($progs_mode=$sql_mode->fetch()){
                                                                ?>
                                                                <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4"  id="module" hidden>
                                                            <label>Module</label>
                                                            <select class="form-control select2" style="width:100%" name="modules[]" id="module_id" multiple>
                                                            </select>
                                                            <span id="spinner4"></span>
                                                        </div>
                                                        
                                                    <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                                        <button type="button" id="load_btn" class="btn btn-icon btn-primary"><span id="spinner5"></span>&nbsp;<span id="indicator5">Load data</span>&nbsp;</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <div class="col-12 col-sm-12 col-lg-12" id="list" hidden>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Marking List</h4>
                                    </div>
                                    <div class="col-md-6 float-right" id="temp-link">
                                    </div>
                                        <!--<div id="template"></div>-->
                                    <div class="card-body table-responsive">
                                        <form id="register_exam_marks" action="register_exam_marks" method="POST">
                                            <input type="hidden" name="action" value="register_exam_marks">
                                            <input type="hidden" name="m_module" id="m_module">
                                            <input type="hidden" name="m_intake" id="m_intake">
                                            <input type="hidden" name="m_splz" id="m_splz">
                                            <table class="table table-hover table-sm">
                                                <thead id="module-names">
                                                    <!--<tr>-->
                                                    <!--    <th scope="col">#</th>-->
                                                    <!--    <th scope="col" class="d-none d-sm-table-cell">Names</th>-->
                                                    <!--    <th scope="col">Registration Number <?php echo ($_request['module_id']);?></th>-->
                                                    <!--    <th ></th>-->
                                                    <!--    <div ></div>-->
                                                    <!--    <th scope="col">-->
                                                    <!--        <div style="display:flex; flex-direction:row;">-->
                                                    <!--            <label style="margin-top:5px;">CAT/</label>&nbsp;-->
                                                    <!--            <input type="number" id="permarks_cat" name="permarks_cat" class="form-control" style="width:70px; height:30px;" readonly>-->
                                                    <!--        </div>-->
                                                    <!--    </th>-->
                                                    <!--    <th scope="col">-->
                                                    <!--        <div style="display:flex; flex-direction:row;">-->
                                                    <!--            <label style="margin-top:5px;">EXAM/</label>&nbsp;-->
                                                    <!--            <input type="number" id="permarks_exam" name="permarks_exam" class="form-control" style="width:70px; height:30px;" readonly>-->
                                                    <!--        </div>-->
                                                    <!--    </th>-->
                                                    <!--    <th scope="col">Selection</th>-->
                                                    <!--</tr>-->
                                                </thead>
                                                <tbody id="students">
                                                </tbody>
                                            </table> 
                                            <div class="buttons" style="display:flex;flex-direction:row;justify-content:center;margin-top:50px;">
                                                <button type="submit" class="btn btn-icon btn-primary btn-sm col-5 col-sm-3 col-lg-2" id="m_Btn"><span id="spinner0"></span>&nbsp;<i class="fas fa-briefcase"></i>&nbsp;<span id="indicator0">Save marks</span>&nbsp;</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-whitesmoke" style="display:flex; flex-direction:row; justify-content:center;">
                                        <button type="button" class="btn btn-icon btn-success btn-sm col-5 col-sm-3 col-lg-2" id="export"><span id="spinner001"></span>&nbsp;<i class="fas fa-download"></i>&nbsp;<span id="indicator001">Export Excel</span>&nbsp;</button>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-icon btn-success btn-sm col-5 col-sm-3 col-lg-2" id="upload"><span id="spinner002"></span>&nbsp;<i class="fas fa-upload"></i>&nbsp;<span id="indicator002">Upload Excel</span>&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    <!--update modal-->
        <form action="upload_exam_marks.php" method="POST" id="upload_exam_marks" enctype="multipart/form-data">
            <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Uploading Marks in CSV Format</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="action" value="upload_marks">
                            <div class="form-group">
                                <input type="hidden" name="u_splz" id="u_splz">
                                <input type="hidden" name="u_intake" id="u_intake">
                                <input type="hidden" name="u_module" id="u_module">
                            </div>
                            <div class="form-group">
                                <label>Upload your file</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-clipboard"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="upload_btn"><span id="spinner_u"></span>&nbsp;<span id="indicator_u">Upload</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <!--end update modal-->
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
//load specs
        $('#prg_type').change(function () {
            var getData= {
                    prg_type:$(this).val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#spec").attr('hidden',false);
                    $("#splz_id").empty();
                    $('#spinner1').fadeOut('fast');
                    if(data.length>0){
                        $("#splz_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                            });
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        //load intakes
        $('#prg_type').change(function () {
            var getData= {
                    type:$(this).val(),
                    action:'load_all_intakes'
                };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#intake_id").empty();
                    if(data.length>0){
                        $("#intake_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"|" + value.acad_year+"</option>");
                            });
                    }
				},
				error:function(error){
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        //load levels
        $('#splz_id').change(function () {
            var getData= {
                    type:$("#prg_type").val(),
                    action:'load_levels'
                    };
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#level").attr('hidden',false);
                    $("#intake").attr('hidden',false);
                    $("#level_id").empty();
                    $('#spinner2').fadeOut('fast');
                    if(data.length>0){
                        $("#level_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner2').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
//load modules
        $('#level_id').change(function () {
            var getData= {
                    splz_id:$("#splz_id").val(),
                    level_id:$("#level_id").val(),
                    action:'load-modules'
                    };
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Specialization/spec_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#mode").attr('hidden',false);
                    $("#module_id").empty();
                    $('#spinner3').fadeOut('fast');
                    if(data.length>0){
                        $("#module_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#module_id").append("<option value='" + value.module_id + "'>" + value.module_name +" ( "+value.module_code+" )</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
//load modes
        $('#mode_id').change(function () {
            $("#module").attr('hidden',false);
        });
        
         $('#module').change(function () {
            $("#loader").attr('hidden',false);
        });
        
        $("#excel-temp").click(function(){
            var intake =$("#intake_id").val();
            var splz =$("#splz_id").val();
            var modules =$("#module_id").val();
            var mode =$("#mode_id").val();
            var level =$("#level_id").val();
            $('#excel-spinner').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');      
            $.post('?intake=' + intake + '&splz='+ splz + '&modules=' + modules + '&mode='+ mode + '&level='+ level , function (data) {
             $("#template").html(data);
                $('#excel-spinner').fadeOut('fast');
                // alert(modules);
            });
        });
 
//load class list
        $(document).on('click','#load_btn',function () {
            console.log("mods",$("#module_id").val());
            $("#register_exam_marks")[0].reset();
            var intake = $("#intake_id").val();
            var splz = $("#splz_id").val();
            var modules = $("#module_id").val();
            var mode = $("#mode_id").val();
            var level = $("#level_id").val();
            var prgType =$("#prg_type").val();
            
            var getData= {
                    intake:$("#intake_id").val(),
                    splz:$("#splz_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'load-exam-mark-list'
                    };
            $("#m_module").val($("#module_id").val());
            $("#m_splz").val($("#splz_id").val());
            $("#m_intake").val($("#intake_id").val());
            //disable_inputs();
            $("#list").attr('hidden', true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator5').html("loading...")
            
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    console.log("datalist", data);
                    enable_inputs();
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("load data");
                    var html='';
                    var heading ='';
                    var i=1;
                    var temp ='';
                    
                    var moduleNames = [];
                    var marks ={};
                    var cats ={};
                    var catCounter =0;
                    var examCounter =0;
                    var slicer =data[0][0].length;

                    for (var counter = 0; counter < data.length; counter++) {
                        var currentItem = data[counter];
                        var structuresCounter =0;
                    
                        if (Array.isArray(currentItem)) {
                            for (var j = 0; j < currentItem.length; j++) {
                                if (Array.isArray(currentItem[j])) {
                                    
                                    currentItem[j].forEach(function (modName) {
                                        
                                        if (!cats[structuresCounter]) {
                                            cats[structuresCounter] = [];
                                            marks[structuresCounter] = [];
                                        }
                                        if (modName && 'module-name' in modName) {
                                            moduleNames.push(modName['module-name']);
                                        }
                                        if(modName && 'cat' in modName){
                                            cats[structuresCounter].push(modName.cat);
                                        }
                                        if(modName && 'final_exam' in modName){
                                            marks[structuresCounter].push(modName.final_exam);
                                        }
                                    });
                                }
                             }
                             structuresCounter +=1;
                        } else if (currentItem && 'module-name' in currentItem) {
                            moduleNames.push(currentItem['module-name']);
                        }
                    }
                    var slicedCats = [];
                    var slicedExams = [];
                    const studentSubarrays = [];
                    var size =moduleNames.length;
                    
                    for (let student = 0; student < slicer; student++) {
                        const subarray = [];
                        for (let module = 0; module < size; module++) {
                            const index = student + slicer * module;
                            subarray.push(cats[0][index]);
                        }
                        slicedCats.push(subarray);
                    }
                    
                    for (let student = 0; student < slicer; student++) {
                        const subarray = [];
                        for (let module = 0; module < size; module++) {
                            const index = student + slicer * module;
                            subarray.push(marks[0][index]);
                        }
                        slicedExams.push(subarray);
                    }

                    console.log("names", moduleNames);
                    console.log("cats", cats);
                    console.log("marks", marks);
                    console.log("sliced cats", slicedCats);
                    console.log("sliced exams", slicedExams);
                    console.log("slicer ", slicer);
                    
                    heading += '<tr>';
                    heading += '<th scope="col" class="d-none d-sm-table-cell" colspan =1>' + "#" + '</th>';
                    heading += '<th scope="col" class="d-none d-sm-table-cell" colspan =1>' + "Names" + '</th>';
                    heading += '<th scope="col" class="d-none d-sm-table-cell" colspan =1>' + "Registration" + '</th>';
                    
                    for (var counter = 0; counter < moduleNames.length; counter++) {
                        heading += '<th scope="col" class="d-none d-sm-table-cell" colspan =2>' + moduleNames[counter] + '</th>';
                    }
                    heading += '<th scope="col" class="d-none d-sm-table-cell" colspan =1>' + "Selection" + '</th>';
                    heading += '</tr>';
                        
                    if(data[0].length>0){
                        for (var countedSize = 0; countedSize < data.length; countedSize++) {
                            var currentArray = data[countedSize];
                            var subJ =0;
                            // console.log("data ",currentArray );
                            
                            if(countedSize>0){
                                continue;
                            }else{
                                for (var j = 0; j < currentArray.length; j++) {
                                    
                                    if(j>0){
                                        continue;
                                    }else{
                                        var stu = currentArray[j];
                                        stu.forEach(function(studentData){
                                             var myCounter =0;
                                            // Access the 'cat' property from each student object
                                            var cat =studentData.cat;
            
                                            console.log("stu ",stu );
                                            html += '<tr>';
                                            html += '<td>' + i+'</td>';
                                            html += '<td>'+studentData.reg_no+'</td>';
                                            html += '<td class="d-none d-sm-table-cell">' + studentData.lname+" "+studentData.fname+ '</td>';
                                            for (var counter = 0; counter < moduleNames.length; counter++) {
                                                var realIdname =moduleNames[counter].replace(/\s/g, '');
                                                html += '<td><input type="number" class="form-control marks" style="width:90px; height:30px;" id="cat_' + realIdname + studentData.reg_no + '" name="cat_' + realIdname + studentData.reg_no + '" value="' + slicedCats[subJ][counter] + '" step=".01" min="0" placeholder="CAT"></td>';
                                                html += '<td><input type="number" class="form-control marks" style="width:90px; height:30px;" id="exam_' + realIdname + studentData.reg_no + '" name="exam_' + realIdname + studentData.reg_no + '" value="' + slicedExams[subJ][counter] + '" step=".01" min="0" placeholder="Exam"></td>';
                                                catCounter += stu.length;
                                                examCounter += stu.length;
                                                myCounter +=0;
                                            }
                                             html += '<td><input type="checkbox" class="form-control" style="width:15px;height:15px" name="stu[]" value="'+studentData.reg_no+'" id="stu_'+studentData.reg_no+'"></td>';
                                            i++;
                                            // '+studentData.reg_no+';
                                            subJ +=1;
                                        });
                                    }
                                }
                            }
                        }
                        
                        $("#permarks_cat").val(data[1].cat);
                        $("#permarks_exam").val(data[1].exam);
                        $("#list").attr('hidden',false);
                        $('#students').html(html);
                        $("#module-names").html(heading);
                        var url = 'pull_excel_temp.php?intake=' + intake + '&splz=' + splz + '&modules=' + modules + '&mode=' + mode + '&level=' + level + '&prgType=' + prgType;
                        var temp = '<a href="' + url + '" class="btn btn-primary">';
                        temp += '<i class="fas fa-file-excel" aria-hidden="true"></i> Excel Temp';
                        temp += '</a>';
                        $("#temp-link").html(temp);
                    }
                    else{
                        pop_info("no data found!")
                    }
				},
				error:function(xhr, status, error){
				    console.log("Error:", error); // Display the error message
                    console.log("Status:", status); // Display the status of the request
                    console.log("XHR Object:", xhr); 
				    enable_inputs();
				    $('#indicator5').html("load data");
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("error occured!")
				}
            });
        });
        
    $(document).on('keyup','.marks',function(){
        var in_id=$(this).attr("id");
        const realReg = in_id.substring(in_id.lastIndexOf('EACC'));
        // console.log("Id ",realReg);
        // var reg_no=in_id.substring(5);
        $("#stu_"+realReg).attr('checked',true);
        
        if($("#catm_"+in_id).val()==='' && $("#exam_"+in_id).val()===''){
            $("#stu_"+realReg).removeAttr('checked');
        }
    })
    //save marks
    $("#register_exam_marks").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator0').html("Saving...");
            $(".marks").attr('disabled', true);
            $.ajax({
                url: "/files/Marks/mark_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $(".marks").attr('disabled', false);
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save marks");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $(".marks").attr('disabled', false);
                    $('#spinner0').fadeOut('fast');
                    $('#indicator0').html("Save marks");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
    //export modules
    $('#export').click(function () {
        var formData = {
            prg_type:$("#prg_type").val(),
            intake:$("#intake_id").val(),
            level:$("#level_id").val(),
            splz:$("#splz_id").val(),
            module:$("#module_id").val(),
            mode:$("#mode_id").val(),
            action:'load-exam-mark-list-csv'
            }
        $('#spinner001').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator001').html("Exporting...");
        
        $.ajax({
            url: "/files/Marks/pull_excel_temp_filled.php",
            type: "POST",
            data: formData,
            success: function(data){
                $('#spinner001').fadeOut('fast');
                $('#indicator001').html("Export"); 
                window.location.href = '/files/Marks/'+data;
                pop_up_success("List exported successfully!");
            },
            error: function(){
                $('#spinner001').fadeOut('fast');
                $('#indicator001').html("Export");
                pop_wrong("Something went wrong!");
            }
        });

    });
  
        
//upload modal
        $(document).on('click','#upload',function () {
            $("#u_splz").val($("#splz_id").val());
            $("#u_intake").val($("#intake_id").val());
            $("#u_module").val($("#module_id").val());
            $('#uploadModal').modal('show');
        });
        
// upload marks

    $("#upload_exam_marks").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner_u').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator_u').html("Uploading...");
            $("#upload_btn").attr('disabled', true);
            $.ajax({
                url: "upload_exam_marks.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $("#upload_btn").attr('disabled', false);
                    $('#spinner_u').fadeOut('fast');
                    $('#indicator_u').html("Upload");
                    if(data.status==200){
                        $("#upload_exam_marks")[0].reset();
                        $("#uploadModal").modal('hide');
                        $("#load_btn").click();
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(xhr, status, error){
                    console.log("Error:", error); // Display the error message
                    console.log("Status:", status); // Display the status of the request
                    console.log("XHR Object:", xhr); 
                    $("#upload_btn").attr('disabled', false);
                    $('#spinner_u').fadeOut('fast');
                    $('#indicator_u').html("Upload");
                    // pop_wrong("something went wrong");
                     jQuery(function validation(){
                        swal("Done ", "You can now check marks", "success", {
                            button: "Ok",
                        });
                    });
                    setTimeout(function() {
                        window.location.href = 'edu?mis=regmks';
                    }, 3000);
                }
             });
          });
          
          $("#multi-upload").submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            // $('#indicator').html("Loading...");
            $("#edit-btn").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/files/Marks/upload_multiple.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType:false,
                processData:false,
                success: function (data) {
                     if(data.status==200){
                        $("#upload_exam_marks")[0].reset();
                        $("#uploadModal").modal('hide');
                        $("#load_btn").click();
                        Query(function validation(){
                            swal("Great ", data.message, "success", {
                                button: "Ok",
                            });
                        });
                        setTimeout(function() {
                            window.location.href = 'edu?mis=regmks';
                        }, 3000); 
                    }
                    if(data.status==401){
                       Query(function validation(){
                            swal("Sorry", data.message, "error", {
                                button: "Ok",
                            });
                        });
                        setTimeout(function() {
                            window.location.href = 'edu?mis=regmks';
                        }, 3000); 
                    }
                    if(data.status==500){
                        Query(function validation(){
                            swal("Warning ", data.message, "error", {
                                button: "Ok",
                            });
                        });
                        setTimeout(function() {
                            window.location.href = 'edu?mis=regmks';
                        }, 3000); 
                    }
                 },
                error:function(error){
                    $('#spinner').fadeOu
                    jQuery(function validation(){
                        swal("Error ", "Somethig Went Wrong", "error", {
                            button: "Ok",
                        });
                    });
                    setTimeout(function() {
                        window.location.href = 'edu?mis=regmks';
                    }, 3000);       
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
   function pop_info(feedback) {
        iziToast.warning({
        title: 'info:',
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
    function disable_inputs(){
        $('#prg_type').attr('disabled',true);
        $('#splz_id').attr('disabled',true);
        $('#intake_id').attr('disabled',true);
        $('#level_id').attr('disabled',true);
        $('#module_id').attr('disabled',true);
        $('#mode_id').attr('disabled',true);
    }
    function enable_inputs(){
        $('#prg_type').attr('disabled',false);
        $('#splz_id').attr('disabled',false);
        $('#intake_id').attr('disabled',false);
        $('#level_id').attr('disabled',false);
        $('#module_id').attr('disabled',false);
        $('#mode_id').attr('disabled',false);
    }
</script>