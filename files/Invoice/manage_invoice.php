<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Manage Invoices</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manage Invoices</a></div>
            </div><br>
        </div>
        <div class="section-header">
            <ul class="nav nav-tabs" id="manageTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="individual-tab" data-toggle="tab" href="#individual" role="tab"
                        aria-controls="individual" aria-selected="true">Individual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="class-tab" data-toggle="tab" href="#class" role="tab" aria-controls="class"
                        aria-selected="false">By Class</a>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="manageTabContent">
            <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                <?php include("manage_invoice_individual.php"); ?>
            </div>
            <div class="tab-pane fade" id="class" role="tabpanel" aria-labelledby="class-tab">
                <?php include("manage_invoice_class.php"); ?>
            </div>
        </div>
    </section>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {

    // ======================= SHARED HELPERS =======================

    // Store current invoice data for PDF generation
    var miCurrentData = [];
    var miCurrentStudent = '';
    var miCurrentAcadYear = '';
    var mcCurrentData = [];
    var mcCurrentStudent = '';
    var mcCurrentAcadYear = '';

    function getStatusBadge(inv) {
        if (inv.payment_status == 1) {
            return '<span class="badge badge-success">Paid</span>';
        }
        if (inv.approval_status == 2) {
            return '<span class="badge badge-danger">Cancelled</span>';
        }
        return '<span class="badge badge-warning">Unpaid</span>';
    }

    function getActionButtons(inv) {
        if (inv.payment_status == 1) {
            return '<span class="text-muted">-</span>';
        }
        if (inv.approval_status == 2) {
            return '<button class="btn btn-sm btn-success btn_reactivate" data-id="' + inv.id + '">' +
                '<i class="fas fa-redo"></i> Reactivate</button>';
        }
        return '<button class="btn btn-sm btn-danger btn_cancel" data-id="' + inv.id + '">' +
            '<i class="fas fa-times"></i> Cancel</button>';
    }

    function getFeeType(inv) {
        if (inv.special_fee_id && inv.special_fee_id != '' && inv.special_fee_id != '0') {
            return '<span class="badge badge-info">Special</span>';
        }
        return '<span class="badge badge-primary">Regular</span>';
    }

    function renderInvoiceRows(data, tbodyId) {
        if (data.length > 0) {
            var i = 1,
                html = '';
            data.forEach(function(inv) {
                html += '<tr>';
                html += '<td>' + i + '</td>';
                html += '<td>' + inv.fee_name + '</td>';
                html += '<td>' + getFeeType(inv) + '</td>';
                html += '<td>' + parseFloat(inv.balance).toLocaleString() + '</td>';
                html += '<td>' + (inv.invoice_date || '-') + '</td>';
                html += '<td>' + (inv.month || '-') + '</td>';
                html += '<td>' + (inv.comment || '-') + '</td>';
                html += '<td>' + getStatusBadge(inv) + '</td>';
                html += '<td>' + getActionButtons(inv) + '</td>';
                html += '</tr>';
                i++;
            });
            $(tbodyId).html(html);
        } else {
            $(tbodyId).html('<tr><td colspan="9" align="center">No invoices found</td></tr>');
        }
    }

    function loadInvoices(reg_no, acad_cycle_id, tbodyId, btn, source) {
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: {
                reg_no: reg_no,
                acad_cycle_id: acad_cycle_id,
                action: 'load_student_invoices'
            },
            dataType: "JSON",
            success: function(data) {
                if (btn) btn.html('<i class="fas fa-eye"></i> View');
                // Store data for PDF
                if (source === 'mi') {
                    miCurrentData = data;
                } else if (source === 'mc') {
                    mcCurrentData = data;
                }
                renderInvoiceRows(data, tbodyId);
            },
            error: function() {
                if (btn) btn.html('<i class="fas fa-eye"></i> View');
                pop_wrong("Something went wrong!");
            }
        });
    }

    // ======================= TAB 1: INDIVIDUAL =======================

    // Student search
    $("#mi_input").keyup(function() {
        var keyword = $(this).val();
        if (keyword.length < 2) return;
        var formData = {
            keyword: keyword,
            action: 'search'
        };
        $("#mi_info").attr("hidden", true);
        $("#mi_invoice_detail").attr("hidden", true);
        $('#mi_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#mi_spinner').html("<i class='fas fa-search'></i>");
                if (data.length > 0) {
                    var i = 1,
                        html = '';
                    data.forEach(function(value) {
                        html += '<tr class="mi_stu" data-id="' + value.reg_no +
                            '" style="cursor:pointer;">';
                        html += '<th>' + i + '</th>';
                        html += '<th>' + value.reg_no + '</th>';
                        html += '<td>' + value.fname + ' ' + value.lname + '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#mi_contents').html(html);
                } else {
                    $('#mi_contents').html(
                        '<tr><td colspan="3" align="center">No data found</td></tr>');
                }
            },
            error: function() {
                $('#mi_spinner').html("<i class='fas fa-search'></i>");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Click student - load academic years
    $(document).on('click', '.mi_stu', function() {
        var student = $(this).data("id");
        var formData = {
            stu: student,
            action: 'load_info_payment'
        };
        $("#mi_input").val(student);
        $("#mi_contents").html("");
        $("#mi_invoice_detail").attr("hidden", true);
        $('#mi_spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#mi_spinner').html("<i class='fas fa-search'></i>");
                if (data.length > 0) {
                    $("#mi_info").removeAttr('hidden');
                    var i = 1,
                        html = '';
                    data.forEach(function(value) {
                        html += '<tr>';
                        html += '<td>' + i + '</td>';
                        html += '<td>' + value.splz_full_name + '</td>';
                        html += '<td>' + value.level_full_name + '</td>';
                        html += '<td>' + value.acad_year + '</td>';
                        html += '<td>' + parseFloat(value.invoice)
                        .toLocaleString() + '</td>';
                        html += '<td>' + parseFloat(value.payment)
                        .toLocaleString() + '</td>';
                        html += '<td>' + parseFloat(value.special || 0)
                            .toLocaleString() + '</td>';
                        html +=
                            '<td><button class="btn btn-sm btn-info mi_view_invoices" ' +
                            'data-reg="' + value.reg_no + '" ' +
                            'data-acad="' + value.acad_cycle_id + '">' +
                            '<i class="fas fa-eye"></i> View</button></td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#mi_academics').html(html);
                } else {
                    $("#mi_info").attr("hidden", true);
                    pop_info("No academic records found.");
                }
            },
            error: function() {
                $('#mi_spinner').html("<i class='fas fa-search'></i>");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Click "View" - load invoice detail
    $(document).on('click', '.mi_view_invoices', function() {
        var reg_no = $(this).data("reg");
        var acad_cycle_id = $(this).data("acad");
        var btn = $(this);
        // Store for PDF
        miCurrentStudent = reg_no;
        miCurrentAcadYear = $(this).closest('tr').find('td:eq(3)').text();
        $('#mi_student_label').text('Student: ' + reg_no + ' | Academic Year: ' + miCurrentAcadYear);
        btn.html('<img src="../../img/ajax_loader.gif" width="15">');
        $('#mi_invoice_detail').removeAttr('hidden');
        loadInvoices(reg_no, acad_cycle_id, '#mi_invoice_rows', btn, 'mi');
    });

    // ======================= TAB 2: BY CLASS =======================

    // Campus change -> load programs
    $("#mc_camp_id").change(function() {
        var campus = $(this).val();
        $('#mc_prg_type_id').css('display', 'none');
        $('#mc_fac_id').css('display', 'none');
        $('#mc_dept_id').css('display', 'none');
        $('#mc_splz_id').css('display', 'none');
        $('#mc_level_id').css('display', 'none');
        $('#mc_student_list_section').attr('hidden', true);
        $('#mc_invoice_detail').attr('hidden', true);
        $('#mc_spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: {
                cid: campus,
                action: "load_program_types"
            },
            dataType: "JSON",
            success: function(data) {
                $('#mc_spinner0').fadeOut('fast');
                $('#mc_prg_type_id').css('display', 'block');
                $("#mc_prg_type_id").empty().append(
                    "<option value='' disabled selected>Select Program</option>");
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#mc_prg_type_id").append("<option value='" + value
                            .prg_type_id + "'>" + value.prg_type_full_name +
                            " [" + value.prg_type_short_name + "]</option>");
                    });
                }
            },
            error: function() {
                $('#mc_spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Program change -> load schools
    $("#mc_prg_type_id").change(function() {
        var program = $(this).val();
        $('#mc_fac_id').css('display', 'none');
        $('#mc_dept_id').css('display', 'none');
        $('#mc_splz_id').css('display', 'none');
        $('#mc_level_id').css('display', 'none');
        $('#mc_student_list_section').attr('hidden', true);
        $('#mc_invoice_detail').attr('hidden', true);
        $('#mc_spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: {
                prg_id: program,
                action: "load_schools"
            },
            dataType: "JSON",
            success: function(data) {
                $('#mc_spinner1').fadeOut('fast');
                $('#mc_fac_id').css('display', 'block');
                $("#mc_fac_id").empty().append(
                    "<option value='' disabled selected>Select School</option>");
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#mc_fac_id").append("<option value='" + value.fac_id +
                            "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function() {
                $('#mc_spinner1').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // School change -> load departments
    $("#mc_fac_id").change(function() {
        var school = $(this).val();
        var program = $("#mc_prg_type_id").val();
        $('#mc_dept_id').css('display', 'none');
        $('#mc_splz_id').css('display', 'none');
        $('#mc_level_id').css('display', 'none');
        $('#mc_student_list_section').attr('hidden', true);
        $('#mc_invoice_detail').attr('hidden', true);
        $('#mc_spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: {
                fac_id: school,
                prg_id: program,
                action: "load_departments"
            },
            dataType: "JSON",
            success: function(data) {
                $('#mc_spinner2').fadeOut('fast');
                $('#mc_dept_id').css('display', 'block');
                $("#mc_dept_id").empty().append(
                    "<option value='' disabled selected>Select Department</option>");
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#mc_dept_id").append("<option value='" + value.dept_id +
                            "'>" + value.dept_full_name + "</option>");
                    });
                }
            },
            error: function() {
                $('#mc_spinner2').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Department change -> load specializations
    $("#mc_dept_id").change(function() {
        var department = $(this).val();
        var program = $("#mc_prg_type_id").val();
        var school = $("#mc_fac_id").val();
        $('#mc_splz_id').css('display', 'none');
        $('#mc_level_id').css('display', 'none');
        $('#mc_student_list_section').attr('hidden', true);
        $('#mc_invoice_detail').attr('hidden', true);
        $('#mc_spinner3').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: {
                dept_id: department,
                prg_type: program,
                fac_id: school,
                action: "load_specialization"
            },
            dataType: "JSON",
            success: function(data) {
                $('#mc_spinner3').fadeOut('fast');
                $('#mc_splz_id').css('display', 'block');
                $("#mc_splz_id").empty().append(
                    "<option value='' disabled selected>Select Specialization</option>");
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#mc_splz_id").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function() {
                $('#mc_spinner3').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Specialization change -> load levels
    $("#mc_splz_id").change(function() {
        var department = $("#mc_dept_id").val();
        var program = $("#mc_prg_type_id").val();
        $('#mc_level_id').css('display', 'none');
        $('#mc_student_list_section').attr('hidden', true);
        $('#mc_invoice_detail').attr('hidden', true);
        $('#mc_spinner4').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: {
                dept_id: department,
                prg_type: program,
                action: "load_levels"
            },
            dataType: "JSON",
            success: function(data) {
                $('#mc_spinner4').fadeOut('fast');
                $('#mc_level_id').css('display', 'block');
                $("#mc_level_id").empty().append(
                    "<option value='' disabled selected>Select Level</option>");
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#mc_level_id").append("<option value='" + value
                            .level_id + "'>" + value.level_full_name +
                            "</option>");
                    });
                }
            },
            error: function() {
                $('#mc_spinner4').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    // All dropdowns selected -> load students
    $(document).on('change', '#mc_camp_id, #mc_prg_type_id, #mc_fac_id, #mc_dept_id, #mc_splz_id, #mc_level_id',
        function() {
            if ($("#mc_camp_id").val() !== null && $("#mc_prg_type_id").val() !== null &&
                $("#mc_fac_id").val() !== null && $("#mc_dept_id").val() !== null &&
                $("#mc_splz_id").val() !== null && $("#mc_level_id").val() !== null) {

                var formdata = {
                    camp_id: $("#mc_camp_id").val(),
                    prg_type_id: $("#mc_prg_type_id").val(),
                    fac_id: $("#mc_fac_id").val(),
                    dept_id: $("#mc_dept_id").val(),
                    splz_id: $("#mc_splz_id").val(),
                    level_id: $("#mc_level_id").val(),
                    action: "load_students"
                };

                $('#mc_student_list_section').attr('hidden', true);
                $('#mc_invoice_detail').attr('hidden', true);
                $('#mc_spinner5').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');

                $.ajax({
                    type: "POST",
                    url: "/files/Specialization/spec_controller.php",
                    data: formdata,
                    dataType: "JSON",
                    success: function(data) {
                        $('#mc_spinner5').fadeOut('fast');
                        if (data.length > 0) {
                            var i = 1,
                                html = '';
                            data.forEach(function(stu) {
                                html += '<tr class="mc_stu_row" data-reg="' + stu
                                    .reg_no + '" style="cursor:pointer;">';
                                html += '<td>' + i + '</td>';
                                html += '<td>' + stu.reg_no + '</td>';
                                html += '<td>' + stu.lname + ' ' + stu.fname + '</td>';
                                html += '</tr>';
                                i++;
                            });
                            $('#mc_students').html(html);
                            if ($.fn.DataTable.isDataTable('#mc_student_table')) {
                                $('#mc_student_table').DataTable().destroy();
                            }
                            $('#mc_student_table').DataTable();
                            $('#mc_student_list_section').removeAttr('hidden');
                        } else {
                            pop_info("No students found!");
                        }
                    },
                    error: function() {
                        $('#mc_spinner5').fadeOut('fast');
                        pop_wrong("Something went wrong!");
                    }
                });
            }
        });

    // Click student row -> load academic years + invoices
    $(document).on('click', '.mc_stu_row', function() {
        var reg_no = $(this).data("reg");
        $('.mc_stu_row').removeClass('table-active');
        $(this).addClass('table-active');
        $('#mc_invoice_detail').attr('hidden', true);

        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: {
                stu: reg_no,
                action: 'load_info_payment'
            },
            dataType: "JSON",
            success: function(data) {
                if (data.length > 0) {
                    var acadHtml =
                        '<div class="mb-3"><label class="font-weight-bold">Select Academic Year: </label> ';
                    acadHtml +=
                        '<select id="mc_acad_select" class="form-control d-inline-block" style="width:auto;">';
                    data.forEach(function(value) {
                        acadHtml += '<option value="' + value.acad_cycle_id +
                            '" data-reg="' + value.reg_no + '">' +
                            value.acad_year + ' - ' + value.level_full_name + ' (' +
                            value.splz_full_name + ')' +
                            '</option>';
                    });
                    acadHtml += '</select></div>';
                    $('#mc_acad_selector').html(acadHtml);
                    $('#mc_invoice_detail').removeAttr('hidden');
                    // Auto-load invoices for first academic year
                    $('#mc_acad_select').trigger('change');
                } else {
                    pop_info("No academic records found for this student.");
                }
            },
            error: function() {
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Academic year dropdown change in Tab 2
    $(document).on('change', '#mc_acad_select', function() {
        var acad_cycle_id = $(this).val();
        var reg_no = $(this).find(':selected').data("reg");
        // Store for PDF
        mcCurrentStudent = reg_no;
        mcCurrentAcadYear = $(this).find(':selected').text().trim();
        $('#mc_invoice_rows').html(
            '<tr><td colspan="9" align="center"><img src="../../img/ajax_loader.gif" width="15"> Loading...</td></tr>'
            );
        loadInvoices(reg_no, acad_cycle_id, '#mc_invoice_rows', null, 'mc');
    });

    // ======================= PDF GENERATION =======================

    function getStatusText(inv) {
        if (inv.payment_status == 1) return 'Paid';
        if (inv.approval_status == 2) return 'Cancelled';
        return 'Unpaid';
    }

    function getFeeTypeText(inv) {
        if (inv.special_fee_id && inv.special_fee_id != '' && inv.special_fee_id != '0') return 'Special';
        return 'Regular';
    }

    function generateInvoicePDF(data, studentReg, acadYear, filename) {
        if (!data || data.length === 0) {
            pop_info("No invoice data to print.");
            return;
        }

        var headers = ['#', 'Fee Name', 'Type', 'Amount', 'Date', 'Month', 'Comment', 'Status'];
        var headerRow = headers.map(function(h) {
            return { text: h, bold: true, fillColor: '#003366', color: '#ffffff', fontSize: 8 };
        });

        var body = [headerRow];
        var total = 0;
        data.forEach(function(inv, idx) {
            var amount = parseFloat(inv.balance) || 0;
            total += amount;
            body.push([
                { text: (idx + 1).toString(), fontSize: 7 },
                { text: inv.fee_name || '', fontSize: 7 },
                { text: getFeeTypeText(inv), fontSize: 7 },
                { text: amount.toLocaleString(), fontSize: 7, alignment: 'right' },
                { text: inv.invoice_date || '-', fontSize: 7 },
                { text: inv.month || '-', fontSize: 7 },
                { text: inv.comment || '-', fontSize: 7 },
                { text: getStatusText(inv), fontSize: 7 }
            ]);
        });

        // Total row
        body.push([
            { text: '', fontSize: 7 },
            { text: 'TOTAL', bold: true, fontSize: 8 },
            { text: '', fontSize: 7 },
            { text: total.toLocaleString(), bold: true, fontSize: 8, alignment: 'right' },
            { text: '', fontSize: 7 },
            { text: '', fontSize: 7 },
            { text: '', fontSize: 7 },
            { text: '', fontSize: 7 }
        ]);

        var docDef = {
            pageOrientation: 'landscape',
            pageSize: 'A4',
            content: [
                { text: 'Njala University - Invoice Report', style: 'header' },
                { text: 'Student: ' + studentReg + '  |  Academic Year: ' + acadYear + '  |  Date: ' + new Date().toLocaleDateString(), style: 'sub', margin: [0, 0, 0, 10] },
                {
                    table: {
                        headerRows: 1,
                        widths: ['auto', '*', 'auto', 'auto', 'auto', 'auto', '*', 'auto'],
                        body: body
                    },
                    layout: 'lightHorizontalLines'
                }
            ],
            styles: {
                header: { fontSize: 14, bold: true, color: '#003366' },
                sub: { fontSize: 9, color: '#666666' }
            }
        };
        pdfMake.createPdf(docDef).download(filename || 'Invoice_Report.pdf');
    }

    function generateClassPDF(students, acadCycleId, acadLabel) {
        if (!students || students.length === 0) {
            pop_info("No students to print.");
            return;
        }

        // Show loading
        $('#mc_btn_class_pdf').html('<img src="../../img/ajax_loader.gif" width="15"> Generating...').prop('disabled', true);

        var completed = 0;
        var totalStudents = students.length;
        var content = [
            { text: 'Njala University - Class Invoice Report', style: 'header' },
            { text: 'Date: ' + new Date().toLocaleDateString(), style: 'sub', margin: [0, 0, 0, 10] }
        ];

        var allResults = [];

        students.forEach(function(stu, index) {
            $.ajax({
                url: "/files/Invoice/invoice_controller.php",
                type: "POST",
                data: { reg_no: stu.reg_no, acad_cycle_id: acadCycleId, action: 'load_student_invoices' },
                dataType: "JSON",
                success: function(data) {
                    allResults[index] = { reg_no: stu.reg_no, name: stu.name, data: data };
                    completed++;
                    if (completed === totalStudents) {
                        buildClassPDF(allResults, content);
                    }
                },
                error: function() {
                    allResults[index] = { reg_no: stu.reg_no, name: stu.name, data: [] };
                    completed++;
                    if (completed === totalStudents) {
                        buildClassPDF(allResults, content);
                    }
                }
            });
        });
    }

    function buildClassPDF(allResults, content) {
        var headers = ['#', 'Fee Name', 'Type', 'Amount', 'Date', 'Month', 'Comment', 'Status'];

        allResults.forEach(function(result, idx) {
            if (idx > 0) {
                content.push({ text: '', pageBreak: 'before' });
            }
            content.push({ text: result.reg_no + ' - ' + result.name, style: 'studentHeader', margin: [0, 10, 0, 5] });

            if (result.data && result.data.length > 0) {
                var headerRow = headers.map(function(h) {
                    return { text: h, bold: true, fillColor: '#003366', color: '#ffffff', fontSize: 8 };
                });
                var body = [headerRow];
                var total = 0;

                result.data.forEach(function(inv, i) {
                    var amount = parseFloat(inv.balance) || 0;
                    total += amount;
                    body.push([
                        { text: (i + 1).toString(), fontSize: 7 },
                        { text: inv.fee_name || '', fontSize: 7 },
                        { text: getFeeTypeText(inv), fontSize: 7 },
                        { text: amount.toLocaleString(), fontSize: 7, alignment: 'right' },
                        { text: inv.invoice_date || '-', fontSize: 7 },
                        { text: inv.month || '-', fontSize: 7 },
                        { text: inv.comment || '-', fontSize: 7 },
                        { text: getStatusText(inv), fontSize: 7 }
                    ]);
                });

                body.push([
                    { text: '', fontSize: 7 },
                    { text: 'TOTAL', bold: true, fontSize: 8 },
                    { text: '', fontSize: 7 },
                    { text: total.toLocaleString(), bold: true, fontSize: 8, alignment: 'right' },
                    { text: '', fontSize: 7 },
                    { text: '', fontSize: 7 },
                    { text: '', fontSize: 7 },
                    { text: '', fontSize: 7 }
                ]);

                content.push({
                    table: {
                        headerRows: 1,
                        widths: ['auto', '*', 'auto', 'auto', 'auto', 'auto', '*', 'auto'],
                        body: body
                    },
                    layout: 'lightHorizontalLines'
                });
            } else {
                content.push({ text: 'No invoices found.', fontSize: 9, italics: true, color: '#999999', margin: [0, 0, 0, 10] });
            }
        });

        var docDef = {
            pageOrientation: 'landscape',
            pageSize: 'A4',
            content: content,
            styles: {
                header: { fontSize: 14, bold: true, color: '#003366' },
                sub: { fontSize: 9, color: '#666666' },
                studentHeader: { fontSize: 11, bold: true, color: '#003366' }
            }
        };
        pdfMake.createPdf(docDef).download('Class_Invoice_Report.pdf');
        $('#mc_btn_class_pdf').html('<i class="fas fa-file-pdf"></i> Print Class PDF').prop('disabled', false);
    }

    // Tab 1 Print PDF
    $(document).on('click', '#mi_btn_pdf', function() {
        generateInvoicePDF(miCurrentData, miCurrentStudent, miCurrentAcadYear, 'Invoice_' + miCurrentStudent + '.pdf');
    });

    // Tab 2 Print PDF (single student)
    $(document).on('click', '#mc_btn_pdf', function() {
        generateInvoicePDF(mcCurrentData, mcCurrentStudent, mcCurrentAcadYear, 'Invoice_' + mcCurrentStudent + '.pdf');
    });

    // Tab 2 Print Class PDF (all students)
    $(document).on('click', '#mc_btn_class_pdf', function() {
        var students = [];
        $('#mc_student_table tbody tr').each(function() {
            var reg = $(this).data('reg');
            var name = $(this).find('td:eq(2)').text().trim();
            if (reg) {
                students.push({ reg_no: reg, name: name });
            }
        });

        if (students.length === 0) {
            pop_info("No students in the list.");
            return;
        }

        // Get the active academic cycle - use the first student to determine it
        var firstStudentReg = students[0].reg_no;
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: { stu: firstStudentReg, action: 'load_info_payment' },
            dataType: "JSON",
            success: function(data) {
                if (data.length > 0) {
                    var acadCycleId = data[0].acad_cycle_id;
                    var acadLabel = data[0].acad_year;
                    generateClassPDF(students, acadCycleId, acadLabel);
                } else {
                    pop_info("Could not determine academic year.");
                }
            },
            error: function() { pop_wrong("Something went wrong!"); }
        });
    });

    // ======================= CANCEL / REACTIVATE =======================

    // Cancel invoice
    $(document).on('click', '.btn_cancel', function() {
        var id = $(this).data("id");
        var btn = $(this);
        swal({
            title: "Are you sure?",
            text: "This will cancel the invoice.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function(willCancel) {
            if (willCancel) {
                $.ajax({
                    url: "/files/Invoice/invoice_controller.php",
                    type: "POST",
                    data: {
                        id: id,
                        action: 'cancel'
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            var row = btn.closest('tr');
                            row.find('td:eq(7)').html(
                                '<span class="badge badge-danger">Cancelled</span>'
                                );
                            row.find('td:eq(8)').html(
                                '<button class="btn btn-sm btn-success btn_reactivate" data-id="' +
                                id + '">' +
                                '<i class="fas fa-redo"></i> Reactivate</button>'
                            );
                        } else {
                            pop_info(data.message);
                        }
                    },
                    error: function() {
                        pop_wrong("Something went wrong!");
                    }
                });
            }
        });
    });

    // Reactivate invoice
    $(document).on('click', '.btn_reactivate', function() {
        var id = $(this).data("id");
        var btn = $(this);
        swal({
            title: "Are you sure?",
            text: "This will reactivate the invoice.",
            icon: "info",
            buttons: true,
        }).then(function(willReactivate) {
            if (willReactivate) {
                $.ajax({
                    url: "/files/Invoice/invoice_controller.php",
                    type: "POST",
                    data: {
                        id: id,
                        action: 're_activate_invoice'
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            var row = btn.closest('tr');
                            row.find('td:eq(7)').html(
                                '<span class="badge badge-warning">Unpaid</span>'
                                );
                            row.find('td:eq(8)').html(
                                '<button class="btn btn-sm btn-danger btn_cancel" data-id="' +
                                id + '">' +
                                '<i class="fas fa-times"></i> Cancel</button>'
                            );
                        } else {
                            pop_info(data.message);
                        }
                    },
                    error: function() {
                        pop_wrong("Something went wrong!");
                    }
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
    iziToast.info({
        title: 'Ooops',
        message: feedback,
        position: 'topCenter'
    });
}

function pop_up_success(feedback) {
    iziToast.success({
        title: 'Info',
        message: feedback,
        position: 'topCenter'
    });
}
</script>