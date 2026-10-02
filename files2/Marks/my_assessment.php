<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="lecturer" value="<?php echo $identification; ?>">
                    <section class="section">
                        <div class="section-header">
                            <h3>My assessments</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Assessment history</a></div>
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
                                                                    $sql_progs=$conn->prepare("SELECT 
                                                                                    distinct(tbl_program_type.prg_type_id),
                                                                                    tbl_program_type.prg_type_full_name,
                                                                                    tbl_campus.camp_full_name,
                                                                                    tbl_modules.prg_type
                                                                                    FROM tbl_module_leader
                                                                                    INNER JOIN tbl_modules ON tbl_module_leader.module_id=tbl_modules.module_id 
                                                                                    INNER JOIN tbl_program_type ON tbl_modules.prg_type=tbl_program_type.prg_type_id 
                                                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id
                                                                                    WHERE tbl_module_leader.staff_id='".$identification."'");
                                                                    $sql_progs->execute();
                                                                    while($prg_type=$sql_progs->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $prg_type['prg_type_id']; ?>"><?php echo $prg_type['prg_type_full_name']." | ".$prg_type['camp_full_name']; ?> </option>
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
                                                                    $sql_intakes=$conn->prepare("SELECT intake_id,intake_month FROM tbl_intake WHERE status=1");
                                                                    $sql_intakes->execute();
                                                                    while($intake=$sql_intakes->fetch()){
                                                                        ?>
                                                                <option value="<?php echo $intake['intake_id']; ?>"><?php echo $intake['intake_month']; ?> </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="level" hidden>
                                                            <label>Level</label>
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
                                                            </select>
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
                                        <h4>Assessment history</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Assessment</th>
                                                    <th scope="col">S/N</th>
                                                    <th scope="col">Marks</th>
                                                    <th scope="col"  class="d-none d-sm-table-cell">Class average</th>
                                                    <th scope="col"  class="d-none d-sm-table-cell">CAT marks</th>
                                                    <th scope="col"  class="d-none d-sm-table-cell">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="assessments">
                                            </tbody>
                                        </table> 
                                    </div>
                                    <div class="card-footer white-smoke">
                                        <div class="col-12" style="display:flex; flex-direction:row; justify-content:center">
                                            <button type="button" id="gen_cat_btn" class="btn btn-icon btn-primary"><span id="spinner50"></span>&nbsp;<span id="indicator50">Generate CAT Marks</span>&nbsp;</button>&nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="a_name"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                        <input type="hidden" name="action" value="update_marks">
                                                        <input type="hidden" id="a_id" name="a_id">
                                                            <div class="form-group" id="assessment">
                                                                <input type="hidden" name="e_splz" id="e_splz">
                                                                <input type="hidden" name="e_intake" id="e_intake">
                                                                <input type="hidden" name="e_assess_no" id="e_assess_no">
                                                                <input type="hidden" name="e_mode" id="e_mode">
                                                                <input type="hidden" name="e_module" id="e_module">
                                                                <label>Assessment Type</label>
                                                                <select class="form-control select2" style="width:100%" name="assess_id" id="assess_id" required>
                                                                    <option></option>
                                                                    <?php
                                                                        $sql_assessments=$conn->prepare("SELECT * FROM tbl_assessment WHERE status=1");
                                                                        $sql_assessments->execute();
                                                                        while($assessment=$sql_assessments->fetch()){
                                                                    ?>
                                                                    <option value="<?php echo $assessment['assess_id']; ?>"><?php echo $assessment['assess_name']; ?> </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Marks</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-slash-forward">/</i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="number" class="form-control" name="e_per_marks" id="a_per_marks" placeholder="50" required>
                                                                </div>
                                                            </div>
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm" id="u_Btn"><span id="spinner01"></span>&nbsp;<span id="indicator01">Save changes</span></button>
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
                    user:$("#lecturer").val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#assessments").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
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
//load levels
        $('#splz_id').change(function () {
            var getData= {
                    splz_id:$(this).val(),
                    user:$("#lecturer").val(),
                    action:'load-levels'
                    };
            $('#intake').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#assessments").html('');
            $("#list").attr('hidden',true);
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
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
                    user:$("#lecturer").val(),
                    action:'load-modules'
                    };
            $('#module').attr('hidden',true);
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#assessments").html('');
            $("#list").attr('hidden',true);
            $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
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
            $('#mode').attr('hidden',true);
            $("#loader").attr('hidden',true);
            $("#assessments").html('');
            $("#list").attr('hidden',true);
            var getData= {
                    module:$(this).val(),
                    user:$("#lecturer").val(),
                    action:'load-modes'
                    };
            $("#m_module").val($('#module_id').val());
            $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#mode").attr('hidden',false);
                    $('#spinner4').fadeOut('fast');
                    if(data.length>0){
                        $("#loader").attr('hidden',false);
                        $("#mode_id").empty();
                        $.each(data, function (index, value) {
                            $("#mode_id").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name +"</option>");
                        });
                    }
				},
				error:function(error){
				    $('#spinner4').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
 
//load class assessment list
        $(document).on('click','#load_btn',function () {
            var getData= {
                    splz:$("#splz_id").val(),
                    intake:$("#intake_id").val(),
                    level:$("#level_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'load-assessment-list'
                    };
            disable_inputs();
            $("#list").attr('hidden', true);
            $("#assessments").html('');
            $("#list").attr('hidden',true);
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            $('#indicator5').html("loading...")
            
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    enable_inputs();
                    $('#spinner5').fadeOut('fast');
                    $('#indicator5').html("load data");
                    var html='';
                    var i=1;
                    if(data.length>0){
                        data.forEach(function(assess) {
                            html += '<tr>';
                            html += '<td>' + i+'</td>';
                            html += '<td>'+assess.assess_name+'</td>';
                            html += '<td>' +assess.assess_no+ '</td>';
                            html += '<td>' +assess.per_marks+ '</td>';
                            html += '<td class="d-none d-sm-table-cell">' +assess.average+ '</td>';
                            html += '<td class="d-none d-sm-table-cell"><input type="checkbox" class="selector" id="selector_'+assess.assess_id+'_'+assess.assess_no+'" data-id="'+assess.assess_id+'" data-ref="'+assess.assess_no+'">&nbsp;<span id="spinners_'+assess.assess_id+'_'+assess.assess_no+'"></span></td>';
                            html += '<td class="d-none d-sm-table-cell"><button type="button" class="btn btn-primary btn-sm modifier" data-id="'+assess.assess_id+'" data-ref="'+assess.assess_no+'"><span id="spinner_'+assess.assess_id+'_'+assess.assess_no+'"></span>&nbsp;<i class="fas fa-edit"></i>edit&nbsp;</button></td>';
                            i++;
                        });
                        $("#list").attr('hidden',false);
                        $('#assessments').html(html);
                        data.forEach(function(assess) {
                            if(assess.status==1){
                                $("#selector_"+assess.assess_id+"_"+assess.assess_no).attr('checked', true);
                            }
                        });
                    }
                    else{
                        pop_info("no data found!")
                    }
				},
				error:function(error){
				    enable_inputs();
				    $('#indicator5').html("load data");
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
//load assessment data
        $(document).on('click','.modifier',function () {
            var getData= {
                    assess_id:$(this).data('id'),
                    assess_no:$(this).data('ref'),
                    splz:$("#splz_id").val(),
                    intake:$("#intake_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'load-assessment-data'
                    };
            var aid=$(this).data('id');
            var ano=$(this).data('ref');
            $("#e_splz").val($("#splz_id").val());
            $("#e_intake").val($("#intake_id").val());
            $("#e_assess_no").val($(this).data('ref'));
            $("#e_mode").val($("#mode_id").val());
            $("#e_module").val($("#module_id").val());
            
            $('#spinner_'+aid+'_'+ano).html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
            disable_inputs();
            $.ajax({
                type: "POST",
                url: "/files/Marks/mark_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner_'+aid+'_'+ano).fadeOut('fast');
                    enable_inputs();
                    $("#a_id").val(data.assess_id);
                    $("#a_per_marks").val(data.per_marks);
                    var selectElement = document.getElementById('assess_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.assess_id + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                      var selectedOptionText = selectedOption.textContent;
                      $("#a_name").html(data.assess_name+" | "+data.assess_no);
                    }
                    $('#updateModal').modal('show');

				},
				error:function(error){
				    $('#spinner_'+aid+'_'+ano).fadeOut('fast');
				    enable_inputs();
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        
        
    $("#update_form").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner01').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator01').html("Saving...");
            $("#u_Btn").attr('disabled', true);
            $.ajax({
                url: "/files/Marks/mark_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $("#u_Btn").attr('disabled', false);
                    $('#spinner01').fadeOut('fast');
                    $('#indicator01').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal").modal('hide');
                        $("#load_btn").click();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#u_Btn").attr('disabled', false);
                    $('#spinner01').fadeOut('fast');
                    $('#indicator01').html("Save changes");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
          
//update marks
        $(document).on('click','.selector',function () {
            var getData= {
                    assess_id:$(this).data('id'),
                    assess_no:$(this).data('ref'),
                    splz:$("#splz_id").val(),
                    intake:$("#intake_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'modify-cat-members'
                    };
            var aid=$(this).data('id');
            var ano=$(this).data('ref');
            swal({
                title: "Are you sure?",
                text: "This assessment will not contribute on CAT marks!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $(".selector").attr('disabled',true);
                    $('#spinners_'+aid+'_'+ano).html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
                    disable_inputs();
                    $.ajax({
                        type: "POST",
                        url: "/files/Marks/mark_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $(".selector").attr('disabled',false);
                            $('#spinners_'+aid+'_'+ano).fadeOut('fast');
                            enable_inputs();
                            if(data.status==200){
                                pop_up_success(data.message);
                                $("#load_btn").click();
                            }
                            if(data.status==401){
                                pop_wrong(data.message);
                            }
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
        				},
        				error:function(error){
        				    $('#spinners_'+aid+'_'+ano).fadeOut('fast');
        				    enable_inputs();
                            pop_wrong("Something went wrong!");
        				}
                    });
                }
                else {
                    swal("Operation cancelled!!");
                }
            });
        });
        
//generate CAT marks
        $(document).on('click','#gen_cat_btn',function () {
            var getData= {
                    splz:$("#splz_id").val(),
                    intake:$("#intake_id").val(),
                    module:$("#module_id").val(),
                    mode:$("#mode_id").val(),
                    action:'generate-cat-marks'
                    };
            swal({
                title: "Are you sure?",
                text: "You are going to generate CAT marks!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $(".selector").attr('disabled',true);
                    disable_inputs();
                    $("#spinner50").html("<img src='../../img/ajax_loader.gif' width='20'>").fadeIn('fast');
                    $.ajax({
                        type: "POST",
                        url: "/files/Marks/mark_controller.php",
                        data: getData,
                        dataType:"json",
                        success:function(data){
                            $(".selector").attr('disabled',false);
                            $("#spinner50").fadeOut('fast');
                            enable_inputs();
                            if(data.status==200){
                                pop_up_success(data.message);
                            }
                            if(data.status==401){
                                pop_wrong(data.message);
                            }
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
        				},
        				error:function(error){
        				    $("#spinner50").fadeOut('fast');
        				    enable_inputs();
                            pop_wrong("Something went wrong!");
        				}
                    });
                }
                else {
                    swal("Operation cancelled!!");
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