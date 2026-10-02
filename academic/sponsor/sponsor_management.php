<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>Sponsorship</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Sponsors</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Choose sponsor</h4>
                                    <div class="card-header-action">
                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="collapse show" id="mycard-collapse">
                                    <div class="">
                                            <div class="card-body row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Sponsor</label>
                                                    <select class="form-control select2" style="width:100%" name="sponsor" id="sponsor">
                                                        <option disabled selected>--choose one--</option>
                                                        <?php
                                                            $sql_prg=$conn->prepare("SELECT * FROM tbl_sponsor WHERE status =1");
                                                            $sql_prg->execute();
                                                            $i=1;
                                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                                        <option value="<?php echo $progs_faculty['spon_id']; ?>"><?php echo $progs_faculty['spon_full_name']; ?> </option>
                                                        <?php } ?>
                                                        <option value="all"><?php echo "All"; ?> </option>
                                                    </select>
                                                    <span id="spinner1"></span>
                                                </div>
                                                
                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="intake" hidden>
                                                    <label>Intake</label>
                                                    <select class="form-control select2" style="width:100%" name="intake_id" id="intake_id">
                                                        <?php
                                                            $sql_intakes=$conn->prepare("SELECT i.intake_id,i.intake_month,ac.acad_year FROM tbl_intake i INNER JOIN tbl_acad_cycle ac ON i.acad_cycle_id = ac.acad_cycle_id WHERE i.status=1");
                                                            $sql_intakes->execute();
                                                            while($intake=$sql_intakes->fetch()){
                                                                ?>
                                                        <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']." | ".$intake['acad_year']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="level" hidden>
                                                   
                                                    <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                                    </select>
                                                    <span id="spinner3"></span>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4"  id="module" hidden>
                                                    <label>Module</label>
                                                    <select class="form-control select2" style="width:100%" name="module_id" id="module_id">
                                                    </select>
                                                    <span id="spinner4"></span>
                                                </div>
                                                <div class="form-group  col-12 col-sm-4 col-lg-4" id="mode" hidden>
                                                    <label>Learning mode</label>
                                                    <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
                                                        <?php
                                                            $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status=1");
                                                            $sql_mode->execute();
                                                            while($progs_mode=$sql_mode->fetch()){
                                                        ?>
                                                        <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <div class="col-12" style="display:flex; flex-direction:row; justify-content:center" id="loader" hidden>
                                                <button type="button" id="load_btn" class="btn btn-icon btn-primary"><span id="spinner5"></span>&nbsp;<span id="indicator5">Print</span>&nbsp;</button>
                                            </div>
                                            </div>
                                            <div "card-body row">
                                                    <div id ="table"></div>
                                                </div>
                                            </div>
                                        </div>
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
    //load specs
        $("#sponsor").change(function () {
            $value =$(this).val();
			$(this).after('<div id="loader"><img src="../../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('sponsor/sponsored_students.php?sponsor=' + $(this).val() , function (data) {
                // alert($value);
             $("#table").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
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
                                $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
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
                    $("#module").attr('hidden',false);
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
        $('#module_id').change(function () {
            $("#mode").attr('hidden',false);
            $("#loader").attr('hidden',false);
        });
 
        //load class list
        $(document).on('click','#load_btn',function () {
            var intake = $("#intake_id").val();
            var splz = $("#splz_id").val();
            var module = $("#module_id").val();
            var mode = $("#mode_id").val();
            var url = "/files/Others/attendance-list?splz="+splz+"&intk="+intake+"&modl="+module+"&md="+mode;
            window.open(url, '_blank');
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