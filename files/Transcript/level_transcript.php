<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Transcript</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Transcript</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
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
                                        $i=1;
                                        while($progs_faculty=$sql_prg->fetch()){
                                            ?>
                                    <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                    <?php } ?>
                                </select>
                                <span id="spinner1"></span>
                            </div>
                            <div class="form-group col-12 col-sm-3 col-lg-3" id="spec" hidden>
                                <label>Specialization</label>
                                <select class="form-control select2" style="width:100%" name="splz_id" id="splz_id">
                                </select>
                                <span id="spinner2"></span>
                            </div>
                            <div class="form-group  col-12 col-sm-3 col-lg-3" id="acad_cycle" hidden>
                                <label>Academic Year</label>
                                <select class="form-control select2" style="width:100%" name="acad_cycle_id" id="acad_cycle_id">
                                    
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-3 col-lg-3" id="level" hidden>
                                <label>Level</label>
                                <select class="form-control select2" style="width:100%" name="level_id" id="level_id">
                                </select>
                                <span id="spinner3"></span>
                            </div>
                            <div class="form-group col-12" style="display: flex; flex-direction: row-reverse">
                                <button type="button" id="cumulative" class="btn btn-icon btn-primary" hidden>Print Cumulative</button>&nbsp;
                                <button type="button" id="singleyear" class="btn btn-icon btn-success" hidden>Print</button>
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
            $('#acad_cycle').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
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
                    pop_wrong("Something went wrong!");
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
                    $("#acad_cycle_id").empty();
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#acad_cycle_id").append("<option value='" + value.acad_cycle_id + "'>"+value.acad_year  +"</option>");
                        });
                    }
				},
				error:function(error){
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //load levels
        //load intakes
        $('#prg_type').change(function () {
            var getData= {
                    type:$(this).val(),
                    action:'load_levels'
                };
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#level_id").empty();
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                    }
                    $('#spec').removeAttr('hidden');
                    $('#acad_cycle').removeAttr('hidden');
                    $('#level').removeAttr('hidden');
                    $("#singleyear").removeAttr('hidden');
                    $("#cumulative").removeAttr('hidden');
				},
				error:function(error){
                    pop_wrong("Something went wrong!");
				}
            });
        }); 

        //print single year transcript
        $(document).on('click','#singleyear',function () {
            var academic = $("#acad_cycle_id").val();
            var splz = $("#splz_id").val();
            var level = $("#level_id").val();
            var url = "/files/Transcript/print_singleyear?splz="+splz+"&academic="+academic+"&lev="+level;
            window.open(url, '_blank');
        });
        
        //print cumulative transcript
        $(document).on('click','#cumulative',function () {
            var academic = $("#acad_cycle_id").val();
            var splz = $("#splz_id").val();
            var url = "/files/Transcript/print_cum_class?splz="+splz+"&academic="+academic;
            window.open(url, '_blank');
        });
    });
</script>