<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Timetable</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Timetable</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>Generate </h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body row">
                                <div class="form-group col-12 col-sm-3 col-lg-3">
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
                                <div class="form-group col-12 col-sm-2 col-lg-2" id="level" hidden>
                                    <label>Level</label>
                                    <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                    </select>
                                    <span id="spinner3"></span>
                                </div>
                                <div class="form-group  col-12 col-sm-3 col-lg-3">
                                    <label>Semester</label><br>
                                    <select class="form-control select2" style="width:100%" name="term_id" id="term_id" required>
                                        <?php
                                            $sql = $conn->prepare("SELECT * FROM  semester ");
                                            $sql->execute();
                                            while($sem = $sql->fetch()){
                                        ?>
                                        <option value="<?php echo $sem['id']; ?>"><?php echo $sem['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                    <button type="button" id="load_btn" class="btn btn-icon btn-primary"><span id="spinner5"></span>&nbsp;<span id="indicator5">Load data</span>&nbsp;</button>&nbsp;
                                    <button type="button" id="special" class="btn btn-icon btn-warning">Special Timetable</button>
                                </div>
                            </div>
                            <div id="information">

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
        $('#prg_type').change(function () {
            var getData= {
                    prg_type:$(this).val(),
                    action:'load-specs'
                };
            
            $('#spec').attr('hidden',true);
            $('#level').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#splz_id").empty();
                    $('#spinner1').fadeOut('fast');
                    if(data.length>0){
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
        
        //load levels
        $('#prg_type').change(function () {
            var getData= {
                    type:$("#prg_type").val(),
                    action:'load_levels'
            };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#spec").removeAttr('hidden');
                    $('#level').removeAttr('hidden');
                    $("#loader").removeAttr('hidden');
                    $("#level_id").empty();
                    $('#spinner2').fadeOut('fast');
                    if(data.length>0){
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
        
        //// Load class information ////
        $(document).on('click', '#load_btn', function(){
            var getData= {
                    prg_type: $("#prg_type").val(),
                    splz: $("#splz_id").val(),
                    level: $("#level_id").val(),
                    semester: $("#term_id").val()
            };
            disable_inputs();
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/generator.php",
                data: getData,
                success:function(data){
                    enable_inputs()
                    $('#spinner5').fadeOut('fast');
                    $('#information').html(data);
                    $('select').select2();
				},
				error:function(error){
				    enable_inputs()
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        })
        
        //// special timetable ////
        $(document).on('click', '#special', function(){
            const prg_type = $("#prg_type").val();
            const splz = $("#splz_id").val();
            const level = $("#level_id").val();
            const semester = $("#term_id").val();

            const url = "edu?mis=sptble&prg=" + encodeURIComponent(prg_type)+"&splz=" + encodeURIComponent(splz) + "&level=" + encodeURIComponent(level) + "&sem=" + encodeURIComponent(semester);
            window.open(url, '_blank');
        })
        
        //load levels
        $(document).on("submit", "#generator", function (e) {
            e.preventDefault();
            var formdata= new FormData(this);
            $('#spinner200').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator200').html("generating...");
            $.ajax({
                type: "POST",
                url: "/files/Timetable/logic.php",
                data: formdata,
                dataType:"JSON",
                contentType: false,
                processData: false,
                success:function(data){
				    $('#spinner200').fadeOut('fast');
				    $('#indicator200').html("Generate");
				    if(data.status == 200){
				    pop_up_success(data.message);
				    } else{
				        pop_info(data.message);
				    }
				},
				error:function(error){
				    $('#spinner200').fadeOut('fast');
				    $('#indicator200').html("Generate");
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
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
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function disable_inputs(){
        $('#prg_type').attr('disabled',true);
        $('#splz_id').attr('disabled',true);
        $('#level_id').attr('disabled',true);
        $('#loader').attr('disabled',true);
    }
    function enable_inputs(){
        $('#prg_type').removeAttr('disabled');
        $('#splz_id').removeAttr('disabled');
        $('#level_id').removeAttr('disabled');
        $('#loader').removeAttr('disabled');
    }
</script>