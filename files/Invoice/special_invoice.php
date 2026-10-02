<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Invoicing</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Special</a></div>
                            </div><br>
                        </div>
                        <div class="section-header">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="ind-tab" data-toggle="tab" href="#ind" role="tab" aria-controls="ind" aria-selected="true">Individual</a></li>
                                <li class="nav-item"><a class="nav-link" id="class-tab" data-toggle="tab" href="#class" role="tab" aria-controls="class" aria-selected="false">Class</a></li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTab3Content">
                            <div class="tab-pane fade show active" id="ind" role="tabpanel" aria-labelledby="ind-tab">
                                <?php include("individual_special.php"); ?>
                            </div>
                            <div class="tab-pane fade" id="class" role="tabpanel" aria-labelledby="class-tab">
                                <?php include("class_special.php"); ?>
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
        
    //////////// individual //////////
    //load student and dept info  
    $("#input").keyup(function(e){
        var formData = {
            keyword:$(this).val(),
            action:'search'
        }
        $("#info").attr("hidden",true);
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>")
                if (data.length > 0) {
                    var i = 1;
                    var html = '';
                    data.forEach(function(value) {
                        var reg = value.reg_no;
                        var names=value.fname+" "+value.lname;
                        html += '<tr class="stu" data-id='+reg+'>';
                        html += '<th>' + i+ '</th>';
                        html += '<th>' + reg+ '</th>';
                        html += '<td>' + names+ '</td>';
                        html += '</tr>';
                        i++;
                    });
                    $('#contents').html(html);
                } else{
                    $('#contents').html('<tr><td colspan="3" align="center">oops, no data found</td></tr>');
                }
            },error: function(){
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $(document).on('click', '.stu', function() {
        var student = $(this).data("id");
        var formData = {
            stu:student,
            action:'load_info_payment'
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
            success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>")
                if(data.length>0){
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
                            
                            // Create a button element
                            var button = document.createElement('a');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'edit');
                            button.setAttribute('href', '?mis=CustInvo&stu='+value.reg_no+'&acad='+value.acad_cycle_id);
                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'Invoice';

                            var row = document.createElement('tr');
                            var cells = [i, splz, lev, acad_year, invoice, payment,''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 6 ? 'td' : 'td');
                                if (index === 6) {
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
                    } else{
                        $('#academics').html('<tr><td colspan="8" align="center">oops, no data found</td></tr>');
                    }
                }
                else{
                    $("#info").attr("hidden",true);
                }
            },error: function(){
                $('#spinner').html("<i class='fas fa-search'></i>")
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    ////////////////////// class ///////////////////////////////
        //load specs
        $('#prg_type').change(function () {
            var getData= {
                    prg_type:$(this).val(),
                    action:'load-specs'
                    };
            $('#spec').attr('hidden',true);
            $('#intke').attr('hidden',true);
            $('#level').attr('hidden',true);
            $('#loader').attr('hidden',true);
            $("#students").html('');
            $("#list").attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner1').fadeOut('fast');
                    $("#splz_id").empty();
                    if(data.length>0){
                        $("#splz_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                        });
                        $("#spec").attr('hidden',false);
                        $('#intke').attr('hidden',false);
                        $('#loader').attr('hidden',false);
                    }
                    else{
                        pop_info("no specializations found!")
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
                        $("#level_id").append("<option disabled selected>--choose one--</option>");
                        $.each(data, function (index, value) {
                                $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                        $("#level").attr('hidden',false);
                    }
                    else{
                        pop_info("no levels found!")
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
    //load students
     $(document).on('change','#splz_id, #level_id, #intake_id',function () {
        if($("#splz_id").val()!==null && $("#level_id").val()!==null && $("#intake_id").val()!==null){
            var formdata = {
                splz: $("#splz_id").val(),
                level: $("#level_id").val(),
                intake: $("#intake_id").val(),
                action: "load_students"
            };
            $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $('#list').attr('hidden',true);
                $.ajax({
                    type: "POST",
                    url: "/files/Specialization/spec_controller.php",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        $('#spinner2').fadeOut('fast');
                       if(data.length>0){
                           var i=1;
                           var html='';
                            data.forEach(function(stu) {
                                html += '<tr>';
                                html += '<td>' + i+'</td>';
                                html += '<td>'+stu.reg_no+'</td>';
                                html += '<td class="d-none d-sm-table-cell">' + stu.lname+" "+stu.fname+ '</td>';
                                html += '<td><input type="checkbox" class="form-control" style="width:15px;height:15px" name="reg_no[]" value="'+stu.reg_no+'" checked></td>';
                                i++;
                            });
                            $('#intake').val($("#intake_id").val());
                            $('#students').html(html);
                            $('#student_list').DataTable().draw();
                            $('#list').attr('hidden',false);
                       }
                       else{
                           pop_info("No Data found!");
                       }
                     },
                    error:function(error){
                        $('#spinner2').fadeOut('fast');
                        pop_info("Something went wrong!");
                    }
                     
                });
            }
        });
    
    //Generate Individual Invoice
    $("#generate_invoice").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator0').html("Saving...");
        $('#is').attr('disabled',true);
        $.ajax({
            url: "/files/Invoice/invoice_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner0').fadeOut('fast');
                $('#indicator0').html("Save invoice");
                if(data.status==200){
                    pop_up_success(data.message);
                    window.location.reload();
                }
                if(data.status==401){
                    pop_info(data.message);
                    $('#is').attr('disabled',false);
                }
            },error: function(){
                $('#spinner0').fadeOut('fast');
                $('#indicator0').html("Save invoice");
                $('#is').attr('disabled',false);
                pop_wrong("Something went wrong!");
            }
        });
    });
          
    //Generate Class Invoice
    $("#generate_class_invoice").submit(function(e){
            e.preventDefault();
        var formData = new FormData(this);
            $('#spinner9').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator9').html("Saving...");
            $('#cs').attr('disabled',true);
            $.ajax({
                url: "/files/Invoice/invoice_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner9').fadeOut('fast');
                    $('#indicator9').html("Save invoice");
                    if(data.status==200){
                        pop_up_success(data.message);
                        window.location.reload();
                    }
                    if(data.status==401){
                        pop_info(data.message);
                        $('#is').attr('disabled',false);
                    }
                },error: function(){
                    $('#spinner9').fadeOut('fast');
                    $('#indicator9').html("Save invoice");
                    $('#cs').attr('disabled',false);
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