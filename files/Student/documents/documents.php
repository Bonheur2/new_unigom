<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>My Documents</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">documents</a></div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                <div class="collapse show">
                                    <div class="card-body row">
                                        <?php
                                            $sql2=$conn->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = '".$identification."'");
                                            $sql2->execute();
                                            $i=1;
                                            while($docs=$sql2->fetch()){
                                                $string=$docs['upload_doc'];
                                                $parts = explode('/', $string);
                                                $after_slash = end($parts);
                                                $parts = explode('_', $after_slash);
                                                $before_underscore = reset($parts);
                                                
                                                $isql2=$conn->prepare("SELECT * FROM tbl_document_type WHERE file_name = '".$before_underscore."'");
                                                $isql2->execute();
                                                $moreInfo=$isql2->fetch();
                                                ?>
                                                
                                                <div class="col-12 col-md-4 col-lg-4">
                                                    <article class="article">
                                                        <div class="article-header">
                                                            <div class="article-image" data-background="../..<?php echo $moreInfo['file_type']=="image"?$docs['upload_doc']:"/student_docs/pdf.png"; ?>">
                                                            </div>
                                                            <div class="article-title">
                                                                <h2><a href="../..<?php echo $docs['upload_doc']; ?>" target="_blank"><?php echo $moreInfo['document_name'] ?></a></h2>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </div>
                                                <?php } ?>
                                    </div>
                                    <div class="card-footer">
                                        <a href="/files/Student/acceptance?k=<?php echo $identification; ?>" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-download"></i> Admission letter</a>
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

    $('#department_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
//load program types
     $("#cump_id").change(function () {
            var campus = $("#cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#prg').css({'display':'none'});
            $('#fct').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#intake').css({'display':'none'});
            $('#mode').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0').fadeOut('fast');
                    $('#prg').css({'display':'block'});
                    $("#prg_type").empty();
                   if(data.length>0){
                        $("#prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +" ["+value.prg_type_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating program types
     $("#e_cump_id").change(function () {
            var campus = $("#e_cump_id").val();
            var formdata = {
                cid: campus,
                action: "load_program_types"
            };
            $('#e_prg').css({'display':'none'});
            $('#e_fct').css({'display':'none'});
            $('#e_dept').css({'display':'none'});
            $('#e_spec').css({'display':'none'});
            $('#e_intake').css({'display':'none'});
            $('#e_mode').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            
            $('#spinner-0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/application/application_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0').fadeOut('fast');
                    $('#e_prg').css({'display':'block'});
                    $("#e_prg_type").empty();
                   if(data.length>0){
                        $("#e_prg_type").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +" ["+value.prg_type_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-0').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
//load faculties
     $("#prg_type").change(function () {
            var p_type = $("#prg_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
            $('#fct').css({'display':'none'});
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#intake').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#fct').css({'display':'block'});
                    $("#fac_id").empty();
                   if(data.length>0){
                        $("#fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
        
//load faculties
     $("#e_prg_type").change(function () {
            var p_type = $("#e_prg_type").val();
            var formdata = {
                type: p_type,
                action: "load_faculties"
            };
            $('#e_fct').css({'display':'none'});
            $('#e_dept').css({'display':'none'});
            $('#e_spec').css({'display':'none'});
            $('#e_intake').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#e_fct').css({'display':'block'});
                    $("#e_fac_id").empty();
                   if(data.length>0){
                        $("#e_fac_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +" ["+value.fac_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load departments
     $("#fac_id").change(function () {
            var fac_id = $("#fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            $('#spinner000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner000').fadeOut('fast');
                    $('#dept').css({'display':'block'});
                    $("#dept_id").empty();
                   if(data.length>0){
                        $("#dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating departments
     $("#e_fac_id").change(function () {
            var fac_id = $("#e_fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
            $('#e_dept').css({'display':'none'});
            $('#e_spec').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            $('#spinner-000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Faculties/faculty_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-000').fadeOut('fast');
                    $('#e_dept').css({'display':'block'});
                    $("#e_dept_id").empty();
                   if(data.length>0){
                        $("#e_dept_id").append("<option></option>");
                        $.each(data, function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +" ["+value.dept_short_name+"]</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load specs
     $("#dept_id").change(function () {
            var dept_id = $("#dept_id").val();
            var formdata = {
                department: dept_id,
                action: "load_specs"
            };
            $('#spec').css({'display':'none'});
            $('#appBtn').css({'display':'none'});
            $('#spinner0000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0000').fadeOut('fast');
                    $('#spec').css({'display':'block'});
                    $("#spcs_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#spcs_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                        $("#appBtn").css({'display':'block'});
                   }
                   
                 },
                error:function(error){
                    $('#spinner0000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load updating specs
     $("#e_dept_id").change(function () {
            var dept_id = $("#e_dept_id").val();
            var formdata = {
                department: dept_id,
                action: "load_specs"
            };
            $('#e_spec').css({'display':'none'});
            $('#e_appBtn').css({'display':'none'});
            $('#spinner-0000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-0000').fadeOut('fast');
                    $('#e_spec').css({'display':'block'});
                    $("#e_spcs_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_spcs_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                        $("#e_appBtn").css({'display':'block'});
                   }
                   
                 },
                error:function(error){
                    $('#spinner-0000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });   

//load intakes
     $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_intakes"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#intake').css({'display':'block'});
                    $('#mode').css({'display':'block'});
                    $("#intake_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

//load intakes
     $("#e_prg_type").change(function () {
            var type = $("#e_prg_type").val();
            var formdata = {
                type: type,
                action: "load_intakes"
            };
             $('#spinner-00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/academic/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner-00').fadeOut('fast');
                    $('#e_intake').css({'display':'block'});
                    $('#e_mode').css({'display':'block'});
                    $("#e_intake_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#e_intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner-00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });
//save application
    $("#save_application").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
            $.ajax({
                url: "/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save option");
                    if(data.status==200){
                        $('#applications').load(location.href + " #applications");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          
//update application
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save changes");
                    if(data.status==200){
                        $('#applications').load(location.href + " #applications");
                        pop_up_success(data.message);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });

//remove application
    $(".delete").click(function(e){
            e.preventDefault();
        var app=$(this).data('id');
        var formData = {
            app:app,
            action:'remove'
        }
            swal({
            title: "Are you sure?",
            text: "Once removed, data will never be recovered!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinnerdel_'+app).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicatordel_'+app).html("removing...");
                    $.ajax({
                        url: "/application/application_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        success: function(data){
                            $('#spinnerdel_'+app).fadeOut('fast');
                            $('#indicatordel_'+app).html("remove");
                            if(data.status==200){
                                $('#applications').load(location.href + " #applications");
                                pop_up_success(data.message);
                            }
                            if(data.status==401){
                                pop_wrong(data.message);
                            }
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                        },error: function(){
                            $('#spinnerdel_'+app).fadeOut('fast');
                            $('#indicatordel_'+app).html("remove");
                            pop_wrong("Something went wrong!");
                        }
                     });
            }
           else {
                swal("Delete Cancelled!!");
            }
        });
    });

        
    //pre-update View
        $(".update").click(function(e){
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_option'
                    };
            $('#spinnerup_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/application/application_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinnerup_'+data_id).fadeOut('fast');
                    $("#app_id").val(data_id);
                    var selectElement0 = document.getElementById('e_cump_id');
                    var selectElement1 = document.getElementById('e_prg_type');
                    var selectElement2 = document.getElementById('e_fac_id');
                    var selectElement3 = document.getElementById('e_dept_id');
                    var selectElement4 = document.getElementById('e_spcs_id');
                    var selectElement5 = document.getElementById('e_intake_id');
                    var selectElement6 = document.getElementById('e_mode');

                    $.each(data[1], function (index, value) {
                            $("#e_prg_type").append("<option value='" + value.prg_type_id + "'>" + value.prg_type_full_name +"</option>");
                        });
                    $.each(data[2], function (index, value) {
                            $("#e_fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                        });
                    $.each(data[3], function (index, value) {
                            $("#e_dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                        });
                    $.each(data[4], function (index, value) {
                            $("#e_spcs_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                    $.each(data[5], function (index, value) {
                            $("#e_intake_id").append("<option value='" + value.intake_id + "'>" + value.intake_month +"</option>");
                        });
                    
                    // Set selected values
                    var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].cump_id + '"]');
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].spcs_id + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].intake_id + '"]');
                    var selectedOption6 = selectElement6.querySelector('option[value="' + data[0].mode + '"]');
                    selectedOption0.selected = true;
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    selectedOption3.selected = true;
                    selectedOption4.selected = true;
                    selectedOption5.selected = true;
                    selectedOption6.selected = true;
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinnerup_'+data_id).fadeOut('fast');
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