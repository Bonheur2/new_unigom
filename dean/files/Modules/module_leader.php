<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
                    <section class="section">
                        <div class="section-header">
                            <h3>Module leaders</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Leaders</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Level information</h4>
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
                                                    <div class="card-body pb-0 row">
                                                        <input type="hidden" name="prg_type" id="prg_type" value="<?php echo $data['prg_type']; ?>">
                                                        <input type="hidden" name="fac_id" id="fac_id" value="<?php echo $data['fac_id']; ?>">

                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Department</label><br>
                                                            <select class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                                                                <option></option>
                                                                <?php
                                                                    $sql=$conn->prepare("SELECT * FROM tbl_department WHERE fac_id IN($faculty) ORDER BY dept_id ASC");
                                                                    $sql->execute();
                                                                    while($department=$sql->fetch()){
                                                                ?>
                                                                <option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_full_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner3"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4" id="spec" hidden>
                                                            <label>Specialization</label><br>
                                                            <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                                            </select>
                                                            <span id="spinner5"></span>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Level</label><br>
                                                            <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                                                <?php 
                                                                    $stmt = $conn->prepare("SELECT * FROM tbl_level WHERE prg_type = '".$data['prg_type']."'");
                                                                    $stmt->execute();
                                                                    while($lev = $stmt->fetch()){
                                                                ?>
                                                                <option value="<?php echo $lev['level_id']; ?>"><?php echo $lev['level_full_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group  col-12 col-sm-4 col-lg-4">
                                                            <label>Learning mode</label><br>
                                                            <select class="form-control select2" style="width:100%" name="mode_id" id="mode_id">
                                                                <option disabled selected>--choose one--</option>
                                                                <?php
                                                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode WHERE status=1");
                                                                    $sql_mode->execute();
                                                                    while($progs_mode=$sql_mode->fetch()){
                                                                ?>
                                                                <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="spinner4"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <div class="col-12 col-sm-12 col-lg-12" id="settings" hidden>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Modules</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <form id="assign_leaders" action="assign_leaders" method="POST">
                                                <input type="hidden" name="a_mode" id="a_mode">
                                                <input type="hidden" name="action" value="assign_leaders">
                                                <table class="table table-hover" id="module_leader">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Module Code</th>
                                                        <th scope="col">Module Name</th>
                                                        <th scope="col">Module Leader</th>
                                                        <th scope="col">Assistants</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="leaders">
                                                    </tbody>
                                                </table> 
                                                <div class="buttons" style="display:flex;flex-direction:row-reverse">
                                                    <button type="submit" class="btn btn-icon btn-primary"><span id="spinner6"></span>&nbsp;<i class="fas fa-gears"></i>&nbsp;<span id="indicator6">Save settings</span>&nbsp;</button>
                                                </div>
                                                 
                                            </form>

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
                   $("#spec").removeAttr("hidden");
                   $("#btn").removeAttr("hidden");
                },
                error:function(error){
                    $('#spinner3').fadeOut('fast');
                    pop_wrong("Something went wrong");
                }
            });
        });
        
        $('#mode_id, #splz_id, #level_id').change(function () {
            if($("#mode_id").val()!=null){
            var getData= {
                    prg:$("#prg_type").val(),
                    fac:$("#fac_id").val(),
                    dept:$("#dept_id").val(),
                    spec:$("#splz_id").val(),
                    lev: $("#level_id").val(),
                    mode: $("#mode_id").val(),
                    cms: $("#camp_id").val(),
                    action:'load-mod-lead'
                    };
            $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Modules/module_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4').fadeOut('fast');
                    $('#settings').attr('hidden',false);
                    $('#a_mode').val($("#mode_id").val());
                    var html='';
                    var i=1;
                    data[0].forEach(function(mod) {
                        html += '<tr>';
                        html += '<td>' + i+ '<input type="hidden" name="module[]" value="'+mod.module_id+'"</td>';
                        html += '<td>' + mod.module_code+ '</td>';
                        html += '<td>' + mod.module_name+ '</td>';
                        html += '<td><select class="form-control new select2" style="width:100%" name="leader_'+mod.module_id+'" id="leader_'+mod.module_id+'"><select></td>';
                        html += '<td><select class="form-control new select2" style="width:100%" name="assistant_'+mod.module_id+'[]" id="assistant_'+mod.module_id+'"><select></td>';
                        i++;
                    });
                    $('#leaders').html(html);
                     // Set leaders
                    data[0].forEach(function(modl) {
                        data[1].forEach(function(value) {
                            $("#leader_"+modl.module_id).append("<option value='" + value.Identification + "'>" + value.first_name +" "+value.family_name+"</option>");
                        });
                        $("#leader_"+modl.module_id).append("<option value=''></option>");
                    });    
                    
                    // set assistants
                    data[0].forEach(function(modl) {
                        data[1].forEach(function(value) {
                            $("#assistant_"+modl.module_id).append("<option value='" + value.Identification + "'>" + value.first_name +" "+value.family_name+"</option>");
                        });
                        $("#assistant_"+modl.module_id).append("<option value=''></option>");
                    }); 
                    
                     // Set assigned leaders    
                    data[0].forEach(function(modle) {
                            var selectElement = document.getElementById("leader_"+modle.module_id);
                            var selectedOption = selectElement.querySelector('option[value="' + modle.staff_id + '"]');
                            var selectedOption2 = selectElement.querySelector('option[value=""]');
                            if(selectedOption){
                                selectedOption.selected = true;
                                $(selectElement).prepend(selectedOption);
                            }
                            else{
                                selectedOption2.selected = true;
                                $(selectElement).prepend(selectedOption2);
                            }
                        });
                     // Set assigned assistants    
                        data[0].forEach(function(modle) {
                            var assistants = JSON.parse(modle.assistants);
                            var selectElement = document.getElementById("assistant_"+modle.module_id);
                            assistants.forEach(function(assistant){
                                var selectedOption = selectElement.querySelector('option[value="' + assistant + '"]');
                                if(selectedOption){
                                    selectedOption.selected = true;
                                }
                            });
                        });
                        
                 $(".new").select2();
				},
				error:function(error){
				    $('#spinner4').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        }
    });
        
        
    //save settings
    $("#assign_leaders").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner6').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator6').html("Saving...");
            $.ajax({
                url: "/files/Modules/module_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner6').fadeOut('fast');
                    $('#indicator6').html("Save settings");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner6').fadeOut('fast');
                    $('#indicator6').html("Save settings");
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