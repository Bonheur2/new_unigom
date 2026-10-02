<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Student Information</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Student Info</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="search by reg. number or names" id="input">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="spinner">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="contents">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="info" hidden> 
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <div style="padding-bottom:8px;">
                                    <table class="table table-sm" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Firstname:</th>
                                            <th scope="col" id="fn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Lastname:</th>
                                            <th scope="col" id="ln"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Gender:</th>
                                            <th scope="col" id="gen"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nationality:</th>
                                            <th scope="col" id="nat"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">ID/passport:</th>
                                            <th scope="col" id="nid"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Father:</th>
                                            <th scope="col" id="ftn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Mother:</th>
                                            <th scope="col" id="mtn"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Address information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Country:</th>
                                            <th scope="col" id="cntr"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Province:</th>
                                            <th scope="col" id="prov"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">District:</th>
                                            <th scope="col" id="dis"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Sector:</th>
                                            <th scope="col" id="sec"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Cell:</th>
                                            <th scope="col" id="cel"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Village:</th>
                                            <th scope="col" id="vil"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Contact information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse3" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse3">
                            <div class="card-body">
                                <div class=" ">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Email:</th>
                                            <th scope="col" id="em" style="max-width: 50px;"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Phone:</th>
                                            <th scope="col" id="pn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Parent's Phone:</th>
                                            <th scope="col" id="ppn"></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Second Phone:</th>
                                            <th scope="col" id="secpn"></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div><br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Academic information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="card-body collapse show" id="mycard-collapse4">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <th>#</th>
                                    <th>Program Type</th>
                                    <th>School</th>
                                    <th>Specialzization</th>
                                    <th>Level</th>
                                    <th>Intake</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                </thead>
                                <tbody id="academics">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
        
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    function load_info(){
        var student = $("#input").val();
        var formData = {
            stu:student,
            action:'load_info'
        }
        $("#contents").html("");
        $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            url: "/files/Student/student_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data){
                $('#spinner').html("<i class='fas fa-search'></i>")
                $("#info").removeAttr("hidden");
                if(data.length>0){
                    //personal
                    $("#fn").html(data[0].fname);
                    $("#ln").html(data[0].lname);
                    $("#nid").html(data[0].ID);
                    $("#nat").html(data[0].nat);
                    $("#gen").html(data[0].gender);
                    $("#ftn").html(data[0].father_names);
                    $("#mtn").html(data[0].mother_names);
                    
                    //address
                    $("#cntr").html(data[0].cname);
                    $("#prov").html(data[0].pname);
                    $("#dis").html(data[0].dname);
                    $("#sec").html(data[0].sname);
                    $("#cel").html(data[0].cellname);
                    $("#vil").html(data[0].vname);
                    //contact 
                    $("#em").html(data[0].email);
                    $("#pn").html(data[0].phone);
                    $("#ppn").html(data[0].parent_phone);
                    $("#secpn").html(data[0].ref_phone);
                        
                        
                    //academics
                    $("#academics").html("");
                    if (data[1].length > 0) {
                        var i = 1;
                        data[1].forEach(function(value) {
                            var prg = value.prg_type_full_name;
                            var fac = value.fac_full_name;
                            var dept = value.dept_full_name;
                            var splz = value.splz_full_name;
                            var lev = value.level_full_name;
                            var acad_year = value.acad_year;
                            var prg_mode = value.prg_mode_full_name;
                            var status = value.status_full_name;

                            var row = document.createElement('tr');
                            var cells = [i, prg, fac, dept, splz, lev, acad_year, prg_mode, status];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement('td');
                                cell.textContent = cellData;
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('academics');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#academics').html('<tr><td colspan="9" align="center">oops, no data found</td></tr>');
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
    }
    $(document).ready(function(){
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
                        $("#info").removeAttr("hidden");
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
                action:'load_info'
            }
            $("#input").val($(this).data("id"));
            $("#contents").html("");
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner').html("<i class='fas fa-search'></i>")
                    if(data.length>0){
                        //personal
                        $("#fn").html(data[0].fname);
                        $("#ln").html(data[0].lname);
                        $("#nid").html(data[0].ID);
                        $("#nat").html(data[0].nat);
                        $("#gen").html(data[0].gender);
                        $("#ftn").html(data[0].father_names);
                        $("#mtn").html(data[0].mother_names);
                        
                        //address
                        $("#cntr").html(data[0].cname);
                        $("#prov").html(data[0].pname);
                        $("#dis").html(data[0].dname);
                        $("#sec").html(data[0].sname);
                        $("#cel").html(data[0].cellname);
                        $("#vil").html(data[0].vname);
                        //contact
                        $("#em").html(data[0].email);
                        $("#pn").html(data[0].phone);
                        $("#ppn").html(data[0].parent_phone);
                        $("#secpn").html(data[0].ref_phone);
                            
                        //academics
                        $("#academics").html("");
                        if (data[1].length > 0) {
                            var i = 1;
                            var html = '';
                            
                            data[1].forEach(function(value) {
                                var prg = value.prg_type_full_name;
                                var fac = value.fac_full_name;
                                var dept = value.dept_full_name;
                                var splz = value.splz_full_name;
                                var lev = value.level_full_name;
                                var acad_year = value.intake_month + '|' + value.acad_year  ;
                                var prg_mode = value.prg_mode_full_name;
                                var status = value.status_full_name;
    
                                var row = document.createElement('tr');
                                var cells = [i, prg, fac, splz, lev, acad_year, prg_mode, status];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement('td');
                                    cell.textContent = cellData;
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('academics');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#academics').html('<tr><td colspan="9" align="center">oops, no data found</td></tr>');
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
    });      
</script>