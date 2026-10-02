<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Invoicing</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Multiple</a></div>
            </div><br>

        </div>
        <div class="section-header">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="known-tab" data-toggle="tab" href="#known"
                        role="tab" aria-controls="known" aria-selected="true">Dynamic Amount Invoicing</a></li>
                <!--<li class="nav-item"><a class="nav-link" id="unknown-tab" data-toggle="tab" href="#unknown" role="tab" aria-controls="unknown" aria-selected="false">Static Amount Invoicing</a></li>-->
            </ul>
        </div>
        <div class="tab-content" id="myTab3Content">
            <div class="tab-pane fade show active" id="known" role="tabpanel" aria-labelledby="known-tab">
                <?php include("dynamic_price_invoicing_class.php"); ?>
            </div>
            <!--<div class="tab-pane fade" id="unknown" role="tabpanel" aria-labelledby="unknown-tab">-->
            <!--    <?php include("static_price_invoicing_class.php"); ?>-->
            <!--</div>-->
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    //new
    $("#e_camp_id").change(function() {
        var campus = $("#e_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#e_prg_type_id').css({
            'display': 'none'
        });
        $('#e_fac_id').css({
            'display': 'none'
        });
        $('#e_dept_id').css({
            'display': 'none'
        });
        $('#e_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for programs
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner0').fadeOut('fast');
                $('#e_prg_type_id').css({
                    'display': 'block'
                }); // Show program section

                // Clear existing program options
                $("#e_prg_type_id").empty();
                $("#e_prg_type_id").append(
                    "<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#e_prg_type_id").append("<option value='" + value
                            .prg_type_id + "'>" + value.prg_type_full_name +
                            " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //static
    $("#s_camp_id").change(function() {
        var campus = $("#s_camp_id").val();
        var formdata = {
            cid: campus,
            action: "load_program_types"
        };

        // Hide program, department, school, and level sections initially
        $('#s_prg_type_id').css({
            'display': 'none'
        });
        $('#s_fac_id').css({
            'display': 'none'
        });
        $('#s_dept_id').css({
            'display': 'none'
        });
        $('#s_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for programs
        $('#spinner0s').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch program types based on selected campus
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner0s').fadeOut('fast');
                $('#s_prg_type_id').css({
                    'display': 'block'
                }); // Show program section

                // Clear existing program options
                $("#s_prg_type_id").empty();
                $("#s_prg_type_id").append(
                    "<option value='' disabled selected>Select Program</option>");

                // Loop through data and add program options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#s_prg_type_id").append("<option value='" + value
                            .prg_type_id + "'>" + value.prg_type_full_name +
                            " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0s').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    $("#e_prg_type_id").change(function() {
        var program = $("#e_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#e_fac_id').css({
            'display': 'none'
        });
        $('#e_dept_id').css({
            'display': 'none'
        });
        $('#e_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for schools
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner1').fadeOut('fast');
                $('#e_fac_id').css({
                    'display': 'block'
                }); // Show school section

                // Clear existing school options
                $("#e_fac_id").empty();
                $("#e_fac_id").append(
                    "<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#e_fac_id").append("<option value='" + value.fac_id +
                            "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //static
    $("#s_prg_type_id").change(function() {
        var program = $("#s_prg_type_id").val();
        var formdata = {
            prg_id: program,
            action: "load_schools"
        };

        // Hide department and level sections initially
        $('#s_fac_id').css({
            'display': 'none'
        });
        $('#s_dept_id').css({
            'display': 'none'
        });
        $('#s_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for schools
        $('#spinner1s').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch schools based on selected program
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner1s').fadeOut('fast');
                $('#s_fac_id').css({
                    'display': 'block'
                }); // Show school section

                // Clear existing school options
                $("#s_fac_id").empty();
                $("#s_fac_id").append(
                    "<option value='' disabled selected>Select School</option>");

                // Loop through data and add school options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#s_fac_id").append("<option value='" + value.fac_id +
                            "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner1s').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    })

    $("#e_fac_id").change(function() {
        var school = $("#e_fac_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id: program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#e_dept_id').css({
            'display': 'none'
        });
        $('#e_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for departments
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2').fadeOut('fast');
                $('#e_dept_id').css({
                    'display': 'block'
                }); // Show department section

                // Clear existing department options
                $("#e_dept_id").empty();
                $("#e_dept_id").append(
                    "<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#e_dept_id").append("<option value='" + value.dept_id +
                            "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //static
    $("#s_fac_id").change(function() {
        var school = $("#s_fac_id").val();
        var program = $("#s_prg_type_id").val();
        var formdata = {
            fac_id: school,
            prg_id: program,
            action: "load_departments"
        };

        // Hide department and level sections initially
        $('#s_dept_id').css({
            'display': 'none'
        });
        $('#s_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for departments
        $('#spinner2ss').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch departments based on selected school
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2ss').fadeOut('fast');
                $('#s_dept_id').css({
                    'display': 'block'
                }); // Show department section

                // Clear existing department options
                $("#s_dept_id").empty();
                $("#s_dept_id").append(
                    "<option value='' disabled selected>Select Department</option>");

                // Loop through data and add department options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#s_dept_id").append("<option value='" + value.dept_id +
                            "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner2ss').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    $("#e_dept_id").change(function() {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var school = $("#e_fac_id").val();

        var formdata = {
            dept_id: department,
            prg_type: program,
            fac_id: school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#e_splz_id').css({
            'display': 'none'
        });

        // Show loading spinner for levels
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner3').fadeOut('fast');
                $('#e_splz_id').css({
                    'display': 'block'
                }); // Show level section

                // Clear existing level options
                $("#e_splz_id").empty();
                $("#e_splz_id").append(
                    "<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#e_splz_id").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //static
    $("#s_dept_id").change(function() {
        var department = $("#s_dept_id").val();
        var program = $("#s_prg_type_id").val();
        var school = $("#s_fac_id").val();

        var formdata = {
            dept_id: department,
            prg_type: program,
            fac_id: school,
            action: "load_specialization"
        };

        // Hide level section initially
        $('#s_splz_id').css({
            'display': 'none'
        });

        // Show loading spinner for levels
        $('#spinner3s').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner3s').fadeOut('fast');
                $('#s_splz_id').css({
                    'display': 'block'
                }); // Show level section

                // Clear existing level options
                $("#s_splz_id").empty();
                $("#s_splz_id").append(
                    "<option value='' disabled selected>Select specialization</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#s_splz_id").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner3s').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    $("#e_splz_id").change(function() {
        var department = $("#e_dept_id").val();
        var program = $("#e_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type: program,
            action: "load_levels"
        };

        // Hide level section initially
        $('#e_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for levels
        $('#spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner4').fadeOut('fast');
                $('#e_level_id').css({
                    'display': 'block'
                }); // Show level section

                // Clear existing level options
                $("#e_level_id").empty();
                $("#e_level_id").append(
                    "<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#e_level_id").append("<option value='" + value.level_id +
                            "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    //static
    $("#s_splz_id").change(function() {
        var department = $("#s_dept_id").val();
        var program = $("#s_prg_type_id").val();
        var formdata = {
            dept_id: department,
            prg_type: program,
            action: "load_levels"
        };

        // Hide level section initially
        $('#e_level_id').css({
            'display': 'none'
        });

        // Show loading spinner for levels
        $('#spinner4s').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

        // AJAX request to fetch levels based on selected department
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner4s').fadeOut('fast');
                $('#s_level_id').css({
                    'display': 'block'
                }); // Show level section

                // Clear existing level options
                $("#s_level_id").empty();
                $("#s_level_id").append(
                    "<option value='' disabled selected>Select Level</option>");

                // Loop through data and add level options
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#s_level_id").append("<option value='" + value.level_id +
                            "'>" + value.level_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner4s').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });





    ////////////////////// Dynamic invoice ///////////////////////////////
    //load specs
    $('#prg_type').change(function() {
        var getData = {
            prg_type: $(this).val(),
            action: 'load-specs'
        };
        $('#spec').attr('hidden', true);
        $('#intke').attr('hidden', true);
        $('#level').attr('hidden', true);
        $('#loader').attr('hidden', true);
        $("#students").html('');
        $("#list").attr('hidden', true);
        $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: getData,
            dataType: "json",
            success: function(data) {
                $('#spinner1').fadeOut('fast');
                $("#splz_id").empty();
                if (data.length > 0) {
                    $("#splz_id").append(
                        "<option disabled selected>--choose one--</option>");
                    $.each(data, function(index, value) {
                        $("#splz_id").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                    $("#spec").attr('hidden', false);
                    $('#intke').attr('hidden', false);
                    $('#loader').attr('hidden', false);
                } else {
                    pop_info("no specializations found!")
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!")
            }
        });
    });

    //load levels
    $('#prg_type').change(function() {
        var getData = {
            type: $(this).val(),
            action: 'load_levels'
        };
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: getData,
            dataType: "json",
            success: function(data) {
                $("#level_id").empty();
                if (data.length > 0) {
                    $("#level_id").append(
                        "<option disabled selected>--choose one--</option>");
                    $.each(data, function(index, value) {
                        $("#level_id").append("<option value='" + value.level_id +
                            "'>" + value.level_full_name + "</option>");
                    });
                    $("#level").attr('hidden', false);
                } else {
                    pop_info("no levels found!")
                }
            },
            error: function(error) {
                $('#spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });



    //load students //dynamic invoice
    $(document).on('change', '#e_camp_id, #e_prg_type_id, #e_fac_id, #e_dept_id, #e_splz_id, #e_level_id',
        function() {
            if ($("#e_camp_id").val() !== null && $("#e_prg_type_id").val() !== null && $("#e_fac_id")
                .val() !== null &&
                $("#e_dept_id").val() !== null && $("#e_splz_id").val() !== null && $("#e_level_id")
                .val() !== null) {

                var formdata = {
                    camp_id: $("#e_camp_id").val(),
                    prg_type_id: $("#e_prg_type_id").val(),
                    fac_id: $("#e_fac_id").val(),
                    dept_id: $("#e_dept_id").val(),
                    splz_id: $("#e_splz_id").val(),
                    level_id: $("#e_level_id").val(),
                    action: "load_students"
                };

                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $('#list').attr('hidden', true);

                $.ajax({
                    type: "POST",
                    url: "/files/Specialization/spec_controller.php",
                    data: formdata,
                    dataType: "JSON",
                    success: function(data) {
                        $('#spinner2').fadeOut('fast');
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            data.forEach(function(stu) {
                                html += '<tr>';
                                html += '<td>' + i + '</td>';
                                html += '<td>' + stu.reg_no + '</td>';
                                html += '<td class="d-none d-sm-table-cell">' + stu
                                    .lname + " " + stu.fname + '</td>';
                                html +=
                                    '<td><input type="checkbox" class="form-control" style="width:15px;height:15px" name="reg_no[]" value="' +
                                    stu.reg_no + '" checked></td>';
                                i++;
                            });

                            $('#u_prg_type_id').val($("#e_prg_type_id").val());
                            $('#u_fac_id').val($("#e_fac_id").val());
                            $('#u_dept_id').val($("#e_dept_id").val());
                            $('#u_splz_id').val($("#e_splz_id").val());
                            $('#u_level_id').val($("#e_level_id").val());

                            updateFeeDropdown();

                            $('#students').html(html);
                            $('#students_special').html(html);
                            $('#student_list').DataTable().draw();
                            $('#student_list_special').DataTable().draw();
                            $('#list').attr('hidden', false);
                        } else {
                            pop_info("No Data found!");
                        }
                    },
                    error: function() {
                        $('#spinner2').fadeOut('fast');
                        pop_info("Something went wrong!");
                    }
                });
            }
        });

    function updateFeeDropdown() {
        let prgTypeId = $('#u_prg_type_id').val();
        let facId = $('#u_fac_id').val();
        let deptId = $('#u_dept_id').val();
        let splzId = $('#u_splz_id').val();
        let levelId = $('#u_level_id').val();


        let feeDropdown = $('select[name="fee_id"]');
        if (feeDropdown.length === 0) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/files/Specialization/spec_controller.php",
            data: {
                action: "filter_fee_categories",
                prg_type_id: prgTypeId,
                fac_id: facId,
                dept_id: deptId,
                splz_id: splzId,
                level_id: levelId
            },
            dataType: "JSON",
            success: function(response) {
                feeDropdown.empty();
                if (response.length > 0) {
                    response.forEach(function(fee) {
                        feeDropdown.append(new Option(fee.name, fee.id));
                    });
                } else {
                    feeDropdown.append(new Option("No Fee Categories Available", ""));
                }
            },
            error: function() {
                console.error("Failed to fetch fee categories");
            }
        });
    }



    //Generate dynamic Invoice
    $("#generate_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save invoice");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 500) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#spinner').fadeOut('fast');
                $('#indicator').html("Save invoice");
                pop_wrong("Something went wrong!");

            }
        });
    });

    // Auto-fill amount for special fee with known price
    $("#class_special_fee_select").on('change', function() {
        var selected = $(this).find('option:selected');
        var hasPrice = selected.data('has-price');
        var amount = selected.data('amount');
        if (hasPrice == 1 && amount > 0) {
            $('#class_special_amount').val(amount);
        } else {
            $('#class_special_amount').val('');
        }
    });

    // Generate class Special Invoice
    $("#generate_class_special_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner_class_sp').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator_class_sp').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#spinner_class_sp').fadeOut('fast');
                $('#indicator_class_sp').html("Save Special Invoice");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 401) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#spinner_class_sp').fadeOut('fast');
                $('#indicator_class_sp').html("Save Special Invoice");
                pop_wrong("Something went wrong!");
            }
        });
    });
    ////////////////////// Static invoice ///////////////////////////////

    //load specs
    $('#s_prg_type').change(function() {
        var getData = {
            prg_type: $(this).val(),
            action: 'load-specs'
        };
        $('#s_spec').attr('hidden', true);
        $('#s_intke').attr('hidden', true);
        $('#s_level').attr('hidden', true);
        $('#s_loader').attr('hidden', true);
        $("#s_students").html('');
        $("#s_list").attr('hidden', true);
        $('#s_spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: getData,
            dataType: "json",
            success: function(data) {
                $('#s_spinner1').fadeOut('fast');
                $("#s_splz_id").empty();
                if (data.length > 0) {
                    $("#s_splz_id").append(
                        "<option disabled selected>--choose one--</option>");
                    $.each(data, function(index, value) {
                        $("#s_splz_id").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                    $("#s_spec").attr('hidden', false);
                    $('#s_intke').attr('hidden', false);
                    $('#s_loader').attr('hidden', false);
                } else {
                    pop_info("no specializations found!")
                }
            },
            error: function(error) {
                $('#s_spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!")
            }
        });
    });

    //load levels
    $('#s_prg_type').change(function() {
        var getData = {
            type: $(this).val(),
            action: 'load_levels'
        };
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: getData,
            dataType: "json",
            success: function(data) {
                $("#s_level_id").empty();
                if (data.length > 0) {
                    $("#s_level_id").append(
                        "<option disabled selected>--choose one--</option>");
                    $.each(data, function(index, value) {
                        $("#s_level_id").append("<option value='" + value.level_id +
                            "'>" + value.level_full_name + "</option>");
                    });
                    $("#s_level").attr('hidden', false);
                } else {
                    pop_info("no levels found!")
                }
            },
            error: function(error) {
                $('#s_spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    //load students //dynamic invoice
    $(document).on('change', '#s_camp_id, #s_prg_type_id, #s_fac_id, #s_dept_id, #s_splz_id, #s_level_id',
        function() {
            if ($("#s_camp_id").val() !== null && $("#s_prg_type_id").val() !== null && $("#s_fac_id")
                .val() !== null &&
                $("#s_dept_id").val() !== null && $("#s_splz_id").val() !== null && $("#s_level_id")
                .val() !== null) {
                var formdata = {
                    camp_id: $("#s_camp_id").val(),
                    prg_type_id: $("#s_prg_type_id").val(),
                    fac_id: $("#s_fac_id").val(),
                    dept_id: $("#s_dept_id").val(),
                    splz_id: $("#s_splz_id").val(),
                    level_id: $("#s_level_id").val(),
                    action: "load_students"
                };
                $('#s_spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $('#s_list').attr('hidden', true);
                $.ajax({
                    type: "POST",
                    url: "/files/Specialization/spec_controller.php",
                    data: formdata,
                    dataType: "JSON",
                    success: function(data) {
                        $('#s_spinner2').fadeOut('fast');
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            data.forEach(function(stu) {
                                html += '<tr>';
                                html += '<td>' + i + '</td>';
                                html += '<td>' + stu.reg_no + '</td>';
                                html += '<td class="d-none d-sm-table-cell">' + stu
                                    .lname + " " + stu.fname + '</td>';
                                html +=
                                    '<td><input type="checkbox" class="form-control" style="width:15px;height:15px" name="reg_no[]" value="' +
                                    stu.reg_no + '" checked></td>';
                                i++;
                            });
                            $('#s_intake').val($("#s_intake_id").val());
                            $('#s_students').html(html);
                            $('#s_student_list').DataTable().draw();
                            $('#s_list').attr('hidden', false);
                        } else {
                            pop_info("No Data found!");
                        }
                    },
                    error: function(error) {
                        $('#s_spinner2').fadeOut('fast');
                        pop_info("Something went wrong!");
                    }

                });
            }
        });

    //Generate static Invoice
    $("#generate_static_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#s_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#s_indicator').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#s_spinner').fadeOut('fast');
                $('#s_indicator').html("Save invoice");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 401) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#s_spinner').fadeOut('fast');
                $('#s_indicator').html("Save invoice");
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

function pop_info(feedback) {
    iziToast.info({
        title: 'Ooops',
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