<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Marks</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Marks (V2)</a></div>
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
                                        <?php 
                                            $stmt = $conn->prepare("SELECT * FROM tbl_faculty WHERE fac_id IN($faculty)");
                                            $stmt->execute();
                                            $data = $stmt->fetch();
                                        ?>
                                        <div class="collapse show" id="mycard-collapse">
                                            <div class="">
                                                    <div class="card-body row">
                                                        <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <label>Specialization</label>
                                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                                <?php 
                                                                    $stmt = $conn->prepare("SELECT * FROM tbl_specialization WHERE fac_id IN($faculty)");
                                                                    $stmt->execute();
                                                                    while($splz = $stmt->fetch()){
                                                                ?>
                                                                <option value="<?php echo $splz['splz_id']; ?>"><?php echo $splz['splz_full_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner2"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Academic Year</label>
                                                            <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id">
                                                                <?php
                                                                    $sql_intakes=$conn->prepare("SELECT * FROM tbl_acad_cycle ORDER BY status ASC");
                                                                    $sql_intakes->execute();
                                                                    while($intake=$sql_intakes->fetch()){
                                                                        
                                                                        ?>
                                                                <option value="<?php echo $intake['acad_cycle_id']; ?>"><?php echo $intake['acad_year']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                                            <label>Level</label>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                                                <option></option>
                                                                <?php 
                                                                    $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type = '".$data['prg_type']."'");
                                                                    $stmt->execute();
                                                                    while($lev = $stmt->fetch()){
                                                                ?>
                                                                <option value="<?php echo $lev['level_id']; ?>"><?php echo $lev['level_full_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner3"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
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
                                    <div class="card-body table-responsive">
                                        <form id="register_exam_marks" action="register_exam_marks" method="POST">
                                            <input type="hidden" name="action" value="register_exam_marks">
                                            <input type="hidden" name="m_acad_cycle_id" id="m_acad_cycle_id">
                                            <input type="hidden" name="m_splz" id="m_splz">
                                            <table class="table table-hover table-sm">
                                                <thead id="module-names">
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
                                <input type="hidden" name="u_acad_cycle_id" id="u_acad_cycle_id">
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
                                    <input type="file" class="form-control" name="csv_file" required>
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
                        $('#module').attr('hidden',false);
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
            });
        });
 
        //load class list
        $(document).on('click','#load_btn',function () {
            $("#register_exam_marks")[0].reset();
            var acad_cycle_id = $("#acad_cycle_id").val();
            var splz = $("#splz_id").val();
            var modules = $("#module_id").val();
            var mode = $("#mode_id").val();
            var level = $("#level_id").val();
            var prgType =$("#prg_type").val();
            
            var getData= {
                    intake:$("#acad_cycle_id").val(),
                    splz:$("#splz_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    level:$("#level_id").val(),
                    prgType:$("#prg_type").val(),
                    action:'load-exam-mark-list'
                    };
            $("#m_splz").val($("#splz_id").val());
            $("#m_acad_cycle_id").val($("#acad_cycle_id").val());
            //disable_inputs();
            $("#list").attr('hidden', true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator5').html("loading...")
            
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller_new.php",
                data: getData,
                success:function(data){
                    enable_inputs();
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("load data");
                    
                    $("#list").attr('hidden',false);
                    $('#students').html(data);
                    
                    var url = 'pull_excel_temp.php?acad_cycle_id=' + acad_cycle_id + '&splz=' + splz + '&modules=' + modules + '&mode=' + mode + '&level=' + level + '&prgType=' + prgType;
                    var temp = '<a href="' + url + '" class="btn btn-primary">';
                    temp += '<i class="fas fa-file-excel" aria-hidden="true"></i> Excel Temp';
                    temp += '</a>';
                    $("#temp-link").html(temp);
				},
				error:function(){
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
        $("#stu_"+realReg).attr('checked',true);
        
        if($("#catm_"+realReg).val()==='' && $("#exam_"+realReg).val()===''){
            $("#stu_"+realReg).removeAttr('checked');
        }
    })
    //save marks
    $("#register_exam_marks").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator0').html("Saving...");
            $.ajax({
                url: "/files/Marks/mark_controller_new.php",
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
        var prg_type = $("#prg_type").val();
        var acad_cycle_id = $("#acad_cycle_id").val();
        var level = $("#level_id").val();
        var splz = $("#splz_id").val();
        var modules = $("#module_id").val();
        var mode = $("#mode_id").val();
        const url = '/files/Marks/pull_excel_temp_filled.php?acad_cycle_id=' + acad_cycle_id + '&splz=' + splz + '&modules=' + modules + '&mode=' + mode + '&level=' + level + '&prg_type=' + prg_type;
        window.open(url);
    });
  
        
//upload modal
        $(document).on('click','#upload',function () {
            $("#u_splz").val($("#splz_id").val());
            $("#u_acad_cycle_id").val($("#acad_cycle_id").val());
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
                    $("#upload_btn").attr('disabled', false);
                    $('#spinner_u').fadeOut('fast');
                    $('#indicator_u').html("Upload");
                     jQuery(function validation(){
                        swal("Done ", "You can now check marks", "success", {
                            button: "Ok",
                        });
                    });
                }
             });
          });
          
          $("#multi-upload").submit(function (e) {
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
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
                    }
                    if(data.status==401){
                       Query(function validation(){
                            swal("Sorry", data.message, "error", {
                                button: "Ok",
                            });
                        });
                    }
                    if(data.status==500){
                        Query(function validation(){
                            swal("Warning ", data.message, "error", {
                                button: "Ok",
                            });
                        });
                    }
                 },
                error:function(error){
                    $('#spinner').fadeOu
                    jQuery(function validation(){
                        swal("Error ", "Somethig Went Wrong", "error", {
                            button: "Ok",
                        });
                    });
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