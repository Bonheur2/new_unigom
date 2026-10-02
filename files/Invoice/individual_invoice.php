<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Invoicing</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Individual</a></div>
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
                <?php include("dynamic_price_invoicing.php"); ?>
            </div>
            <!--<div class="tab-pane fade" id="unknown" role="tabpanel" aria-labelledby="unknown-tab">-->
            <!--    <?php include("static_price_invoicing.php"); ?>-->
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
    //load student and dept info  
    $("#input").keyup(function(e) {
        var formData = {
            keyword: $(this).val(),
            action: 'search'
        }
        $("#info").attr("hidden", true);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    var i = 1;
                    var html = '';
                    data.forEach(function(value) {
                        var reg = value.reg_no;
                        var names = value.fname + " " + value.lname;
                        html += '<tr class="stu" data-id=' + reg + '>';
                        html += '<th>' + i + '</th>';
                        html += '<th>' + reg + '</th>';
                        html += '<td>' + names + '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#contents').html(html);
                } else {
                    $('#contents').html(
                        '<tr><td colspan="3" align="center">oops, no data found</td></tr>'
                        );
                }
            },
            error: function() {
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });

    $(document).on('click', '.stu', function() {
        var student = $(this).data("id");
        var formData = {
            stu: student,
            action: 'load_info_payment'
        }
        $("#input").val($(this).data("id"));
        $("#contents").html("");
        $("#invForm").attr('hidden', true);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    $("#info").removeAttr('hidden');
                    $("#academics").html("");
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';

                        data.forEach(function(value) {
                            var splz = value.splz_full_name;
                            var lev = value.level_full_name;
                            var acad_year = value.acad_year;
                            var invoice = value.invoice;
                            var payment = value.payment;
                            var special = value.special || 0;

                            // Create Invoice button
                            var button = document.createElement('a');
                            button.classList.add('btn', 'btn-sm', 'btn-primary',
                                'edit');
                            button.setAttribute('href', '?mis=indInvo&stu=' + value
                                .reg_no + '&acad=' + value.acad_cycle_id);
                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'Invoice';

                            var row = document.createElement('tr');
                            var cells = [i, splz, lev, acad_year, invoice, payment,
                                special, ''
                            ];
                            cells.forEach(function(cellData, index) {
                                var cell = document.createElement('td');
                                if (index === 7) {
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });

                            var table = document.getElementById('academics');
                            table.appendChild(row);
                            i++;
                        });
                    } else {
                        $('#academics').html(
                            '<tr><td colspan="8" align="center">oops, no data found</td></tr>'
                            );
                    }
                } else {
                    $("#info").attr("hidden", true);
                }
            },
            error: function() {
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });

    //Generate dynamic Invoice
    $("#generate_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator0').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#spinner0').fadeOut('fast');
                $('#indicator0').html("Save");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 401) {
                    pop_info(data.message);
                }
                if (data.status == 500) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#spinner0').fadeOut('fast');
                $('#indicator0').html("Save");
                pop_wrong("Something went wrong!");

            }
        });
    });

    //Generate Special Invoice
    $("#generate_special_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner_sp').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator_sp').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#spinner_sp').fadeOut('fast');
                $('#indicator_sp').html("Save Special Invoice");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 401) {
                    pop_info(data.message);
                }
                if (data.status == 500) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#spinner_sp').fadeOut('fast');
                $('#indicator_sp').html("Save Special Invoice");
                pop_wrong("Something went wrong!");
            }
        });
    });

    // Auto-fill amount when special fee with known price is selected
    $("#special_fee_select").on('change', function() {
        var selected = $(this).find('option:selected');
        var hasPrice = selected.data('has-price');
        var amount = selected.data('amount');
        if (hasPrice == 1 && amount > 0) {
            $('#special_amount').val(amount);
        } else {
            $('#special_amount').val('');
        }
    });


    //load student and dept info  
    $("#input2").keyup(function(e) {
        var formData = {
            keyword: $(this).val(),
            action: 'search'
        }
        $("#info2").attr("hidden", true);
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    var i = 1;
                    var html = '';
                    data.forEach(function(value) {
                        var reg = value.reg_no;
                        var names = value.fname + " " + value.lname;
                        html += '<tr class="stu" data-id=' + reg + '>';
                        html += '<th>' + i + '</th>';
                        html += '<th>' + reg + '</th>';
                        html += '<td>' + names + '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#contents2').html(html);
                } else {
                    $('#contents2').html(
                        '<tr><td colspan="3" align="center">oops, no data found</td></tr>'
                        );
                }
            },
            error: function() {
                $('#spinner2').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });

    $(document).on('click', '.stu', function() {
        var student = $(this).data("id");
        var formData = {
            stu: student,
            action: 'load_info_payment'
        }
        $("#input2").val($(this).data("id"));
        $("#contents2").html("");
        $("#invForm2").attr('hidden', true);
        $('#spinner2').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner2').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    $("#info2").removeAttr('hidden');
                    $("#academics2").html("");
                    if (data.length > 0) {
                        var i = 1;
                        var html = '';

                        data.forEach(function(value) {
                            var splz = value.splz_full_name;
                            var lev = value.level_full_name;
                            var acad_year = value.acad_year;
                            var invoice = value.invoice;
                            var payment = value.payment;
                            var special = value.special || 0;

                            // Create Invoice button
                            var button = document.createElement('a');
                            button.classList.add('btn', 'btn-sm', 'btn-primary',
                                'edit');
                            button.setAttribute('href', '?mis=indInvo&stu=' + value
                                .reg_no + '&acad=' + value.acad_cycle_id);
                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'Invoice';

                            var row = document.createElement('tr');
                            var cells = [i, splz, lev, acad_year, invoice, payment,
                                special, ''
                            ];
                            cells.forEach(function(cellData, index) {
                                var cell = document.createElement('td');
                                if (index === 7) {
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });

                            var table = document.getElementById('academics2');
                            table.appendChild(row);
                            i++;
                        });
                    } else {
                        $('#academics2').html(
                            '<tr><td colspan="8" align="center">oops, no data found</td></tr>'
                            );
                    }
                } else {
                    $("#info2").attr("hidden", true);
                }
            },
            error: function() {
                $('#spinner2').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });

    //Generate static Invoice
    $("#generate_static_invoice").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner3').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator3').html("Saving...");
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data) {
                $('#spinner3').fadeOut('fast');
                $('#indicator3').html("Save");
                if (data.status == 200) {
                    pop_up_success(data.message);
                }
                if (data.status == 401) {
                    pop_info(data.message);
                }
            },
            error: function() {
                $('#spinner3').fadeOut('fast');
                $('#indicator3').html("Save");
                pop_wrong("Something went wrong!");

            }
        });
    });
});

function pop_wrong(feedback) {
    iziToast.warning({
        title: 'info',
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