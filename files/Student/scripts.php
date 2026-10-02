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
                if(data.length>0){
                    $("#acceptance").data('id', student);

                    $("#info").attr("hidden",false);
                    $('#update_p').data("id", student);
                    $('#update_a').data("id", student);
                    $('#update_c').data("id", student);
                    $('#update_chur').data("id", student);
                    $('#update_prev_edu').data("id", student);
                    //personal
                    $("#fn").html(data[0].fname);
                    $("#ln").html(data[0].lname);
                    $("#nid").html(data[0].ID);
                    $("#nat").html(data[0].nat);
                    $("#gen").html(data[0].gender);
                    $("#mstatus").html(data[0].marital_status);
                    $("#dobo").html(data[0].dob);
                    $("#ftn").html(data[0].father_names);
                    $("#mtn").html(data[0].mother_names);
                    $("#intake").html(data[0].intake_month+" | "+data[0].acad_year);
                    
                    //address
                    $("#cntr").html(data[0].cname);
                    $("#prov").html(data[0].pname);
                    $("#dis").html(data[0].dname);
                    $("#sec").html(data[0].street);
                    //contact 
                    $("#em").html(data[0].email);
                    $("#pn").html(data[0].phone);
                    $("#ppn").html(data[0].parent_phone);
                    $("#secpn").html(data[0].ref_phone);
                    $("#kin_na").html(data[0].kin_name);
                    $("#kin_re").html(data[0].kin_relation);
                    $("#kin_ad").html(data[0].kin_address);
                    $("#kin_em").html(data[0].kin_email);
                    $("#kin_te").html(data[0].kin_tel);
                    
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
                            var sponsor = value.spon_full_name;
                        
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'edit');
                            button.setAttribute('data-id', value.reg_prg_id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_e_' + value.reg_prg_id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'edit';

                            var row = document.createElement('tr');
                            var cells = [i, prg, fac, splz, lev, acad_year, prg_mode, status,''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 8 ? 'td' : 'td');
                                if (index === 8) {
                                    button.appendChild(spinnerSpan);
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
                        $('#academics').html('<tr><td colspan="9" align="center">oops, no data found</td></tr>');
                    }
                    
                    //sponsors
                    $("#sponsors").html("");
                    if (data[8].length > 0) {
                        var i = 1;
                        var html = '';
                        
                        data[8].forEach(function(value) {
                            var reg_prg_id = value.reg_prg_id;
                            var level = value.level_full_name;
                            var sponsor = value.spon_full_name;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'edits');
                            button.setAttribute('data-id', reg_prg_id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_s_' + reg_prg_id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'edit';

                            var row = document.createElement('tr');
                            var cells = [i, level, sponsor, ''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 3 ? 'td' : 'td');
                                if (index === 3) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('sponsors');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#sponsors').html('<tr><td colspan="4" align="center">oops, no data found</td></tr>');
                    }
                     
                    //passes
                    $("#passes").html("");
                    if (data[7].length > 0) {
                        var i = 1;
                        var html = '';
                        
                        data[7].forEach(function(value) {
                            var cs = value.course;
                            var gr = value.grade;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'editp');
                            button.setAttribute('data-id', value.id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_f_' + value.id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'edit';

                            var row = document.createElement('tr');
                            var cells = [i, cs, gr,''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 3 ? 'td' : 'td');
                                if (index === 3) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('passes');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#passes').html('<tr><td colspan="4" align="center">oops, no data found</td></tr>');
                    }
                    
                    //previous education
                    $("#prev_edu_data").html("");
                    if (data[9].length > 0) {
                        var i = 1;
                        
                        data[9].forEach(function(value) {
                            var edu_id = value.id;
        					var school = value.school;
        					var year_from = value.year_from;
        					var year_to = value.year_to;
        					var certificate = value.certificate;
        					var award = value.award;
                            
                            // Create a button element
                            var button = document.createElement('button');
                            button.classList.add('btn', 'btn-sm', 'btn-primary', 'deletedu');
                            button.setAttribute('data-id', edu_id);
                            
                            var spinnerSpan = document.createElement('span');
                            spinnerSpan.id = 'spinner_edu_' + edu_id;

                            var textSpan = document.createElement('span');
                            textSpan.textContent = 'remove';

                            var row = document.createElement('tr');
                            var cells = [i, school, year_from, year_to, certificate, award, ''];
                            cells.forEach(function (cellData, index) {
                                var cell = document.createElement(index === 6 ? 'td' : 'td');
                                if (index === 6) {
                                    button.appendChild(spinnerSpan);
                                    button.appendChild(textSpan);
                                    cell.appendChild(button);
                                } else {
                                    cell.textContent = cellData;
                                }
                                row.appendChild(cell);
                            });
                        
                            var table = document.getElementById('prev_edu_data');
                            table.appendChild(row);
                            i++;
                        });
                    } else{
                        $('#prev_edu_data').html('<tr><td colspan="6" align="center">oops, no data found</td></tr>');
                    }
                    
                    //church
                    if(data[10].length>0){
                        $("#chrch").html(data[10].church);
                        $("#cntry").html(data[10].countryn);
                        $("#cty").html(data[10].city);
                        $("#sctr").html(data[10].sector);
                        $("#lcncd").html(data[10].licenced);
                        $("#rdnd").html(data[10].ordained);
                        $("#mnstr").html(data[10].minister);
                        $("#ctvts").html(data[10].activities);
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
                        $("#acceptance").data('id', student);
                        
                        $("#info").attr("hidden",false);
                        $('#update_p').data("id", student);
                        $('#update_a').data("id", student);
                        $('#update_c').data("id", student);
                        $('#update_chur').data("id", student);
                        $('#update_prev_edu').data("id", student);
                        //personal
                        $("#fn").html(data[0].fname);
                        $("#ln").html(data[0].lname);
                        $("#nid").html(data[0].ID);
                        $("#nat").html(data[0].nat);
                        $("#gen").html(data[0].gender);
                        $("#mstatus").html(data[0].marital_status);
                        $("#dobo").html(data[0].dob);
                        $("#ftn").html(data[0].father_names);
                        $("#mtn").html(data[0].mother_names);
                        $("#intake").html(data[0].intake_month+" | "+data[0].acad_year);
                        
                        //address
                        $("#cntr").html(data[0].cname);
                        $("#prov").html(data[0].pname);
                        $("#dis").html(data[0].dname);
                        $("#sec").html(data[0].street);
                        //contact
                        $("#em").html(data[0].email);
                        $("#pn").html(data[0].phone);
                        $("#ppn").html(data[0].parent_phone);
                        $("#secpn").html(data[0].ref_phone);
                        $("#kin_na").html(data[0].kin_name);
                        $("#kin_re").html(data[0].kin_relation);
                        $("#kin_ad").html(data[0].kin_address);
                        $("#kin_em").html(data[0].kin_email);
                        $("#kin_te").html(data[0].kin_tel);
                            
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
                                var acad_year = value.acad_year  ;
                                var prg_mode = value.prg_mode_full_name;
                                var status = value.status_full_name;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'edit');
                                button.setAttribute('data-id', value.reg_prg_id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_e_' + value.reg_prg_id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'edit';
    
                                var row = document.createElement('tr');
                                var cells = [i, prg, fac, splz, lev, acad_year, prg_mode, status,''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 8 ? 'td' : 'td');
                                    if (index === 8) {
                                        button.appendChild(spinnerSpan);
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
                        
                        //passes
                        $("#passes").html("");
                        if (data[7].length > 0) {
                            var i = 1;
                            var html = '';
                            
                            data[7].forEach(function(value) {
                                var cs = value.course;
                                var gr = value.grade;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'editp');
                                button.setAttribute('data-id', value.id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_f_' + value.id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'edit';
    
                                var row = document.createElement('tr');
                                var cells = [i, cs, gr,''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 3 ? 'td' : 'td');
                                    if (index === 3) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('passes');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#passes').html('<tr><td colspan="4" align="center">oops, no data found</td></tr>');
                        }
                        
                        //sponsors
                        $("#sponsors").html("");
                        if (data[8].length > 0) {
                            var i = 1;
                            var html = '';
                            
                            data[8].forEach(function(value) {
                                var reg_prg_id = value.reg_prg_id;
                                var level = value.level_full_name;
                                var sponsor = value.spon_full_name;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'edits');
                                button.setAttribute('data-id', reg_prg_id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_s_' + reg_prg_id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'edit';
    
                                var row = document.createElement('tr');
                                var cells = [i, level, sponsor, ''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 3 ? 'td' : 'td');
                                    if (index === 3) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('sponsors');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#sponsors').html('<tr><td colspan="4" align="center">oops, no data found</td></tr>');
                        }
                        
                        //previous education
                        $("#prev_edu_data").html("");
                        if (data[9].length > 0) {
                            var i = 1;
                            
                            data[9].forEach(function(value) {
                                var edu_id = value.id;
            					var school = value.school;
            					var year_from = value.year_from;
            					var year_to = value.year_to;
            					var certificate = value.certificate;
            					var award = value.award;
                                
                                // Create a button element
                                var button = document.createElement('button');
                                button.classList.add('btn', 'btn-sm', 'btn-primary', 'deletedu');
                                button.setAttribute('data-id', edu_id);
                                
                                var spinnerSpan = document.createElement('span');
                                spinnerSpan.id = 'spinner_edu_' + edu_id;
    
                                var textSpan = document.createElement('span');
                                textSpan.textContent = 'remove';
    
                                var row = document.createElement('tr');
                                var cells = [i, school, year_from, year_to, certificate, award, ''];
                                cells.forEach(function (cellData, index) {
                                    var cell = document.createElement(index === 6 ? 'td' : 'td');
                                    if (index === 6) {
                                        button.appendChild(spinnerSpan);
                                        button.appendChild(textSpan);
                                        cell.appendChild(button);
                                    } else {
                                        cell.textContent = cellData;
                                    }
                                    row.appendChild(cell);
                                });
                            
                                var table = document.getElementById('prev_edu_data');
                                table.appendChild(row);
                                i++;
                            });
                        } else{
                            $('#prev_edu_data').html('<tr><td colspan="6" align="center">oops, no data found</td></tr>');
                        }
                        
                        //church
                        if(data[10].length>0){
                            $("#chrch").html(data[10][0].church);
                            $("#cntry").html(data[10][0].countryn);
                            $("#cty").html(data[10][0].city);
                            $("#sctr").html(data[10][0].sector);
                            $("#lcncd").html(data[10][0].licenced);
                            $("#rdnd").html(data[10][0].ordained);
                            $("#mnstr").html(data[10][0].minister);
                            $("#ctvts").html(data[10][0].activities);
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
        
        //pre-update View personal
        $('#update_p').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_p').fadeOut('fast');
                    $("#stu").val(data_id);
                    $("#fname").val(data[0].fname);
                    $("#lname").val(data[0].lname);
                    $("#enid").val(data[0].ID);
                    $("#mother_names").val(data[0].mother_names);
                    $("#father_names").val(data[0].father_names);
                    $("#dob").val(data[0].dob);
                    var selectElement = document.getElementById('gender');
                    var selectElement2 = document.getElementById('nationality');
                    var selectElement3 = document.getElementById('marital_status');
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].gender + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].nationality + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].marital_status + '"]');
                    if(selectedOption){
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    if(selectedOption2){
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    if(selectedOption3){
                        selectedOption3.selected = true;
                        selectElement3.prepend(selectedOption3);
                    }
                    $('#updateModal_p').modal('show');
				},
				error:function(error){
				    $('#spinner_p').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View contact
        $('#update_c').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_c').fadeOut('fast');
                    $("#stuc").val(data_id);
                    $("#phone").val(data[0].phone);
                    $("#email").val(data[0].email);
                    $("#parent_phone").val(data[0].parent_phone);
                    $("#ref_phone").val(data[0].ref_phone);
                    $("#kin_name").val(data[0].kin_name);
                    $("#kin_relation").val(data[0].kin_relation);
                    $("#kin_address").val(data[0].kin_address);
                    $("#kin_email").val(data[0].kin_email);
                    $("#kin_tel").val(data[0].kin_tel);
                    $('#updateModal_c').modal('show');
				},
				error:function(error){
				    $('#spinner_c').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View address
        $('#update_a').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_a').fadeOut('fast');
                    $("#stua").val(data_id);
                    var selectElement0 = document.getElementById('country');
                    if(data[0].country==160){
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                        var selectElement1 = document.getElementById('province_id');
                        var selectElement2 = document.getElementById('district_id');
                        var selectElement3 = document.getElementById('sector');
                        var selectElement4 = document.getElementById('cell_id');
                        var selectElement5 = document.getElementById('village_id');
                        $.each(data[2], function (index, value) {
                                $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename +"</option>");
                            });
                        $.each(data[3], function (index, value) {
                                $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict +"</option>");
                            });
                        $.each(data[4], function (index, value) {
                                $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector +"</option>");
                            });
                        $.each(data[5], function (index, value) {
                                $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell +"</option>");
                            });
                        $.each(data[6], function (index, value) {
                                $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName +"</option>");
                            });
                        
                        // Set selected values
                        var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].province_id + '"]');
                        var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].district_id + '"]');
                        var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].sector + '"]');
                        var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].cell_id + '"]');
                        var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].village_id + '"]');
                        if(selectedOption1){
                            selectedOption1.selected = true;
                            selectElement1.prepend(selectedOption1);
                        }
                        if(selectedOption2){
                            selectedOption2.selected = true;
                            selectElement2.prepend(selectedOption2);
                        }
                        if(selectedOption3){
                            selectedOption3.selected = true;
                            selectElement3.prepend(selectedOption3);
                        }
                        if(selectedOption4){
                            selectedOption4.selected = true;
                            selectElement4.prepend(selectedOption4);
                        }
                        if(selectedOption5){
                            selectedOption5.selected = true;
                            selectElement5.prepend(selectedOption5);
                        }
                    }
                    else{
                        $("#prov").attr('hidden',true);
                        $("#district").attr('hidden',true);
                        $("#sect").attr('hidden',true);
                        $("#cell").attr('hidden',true);
                        $("#village").attr('hidden',true);
                    }
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                    $('#updateModal_a').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        
        //pre-update View church info
        $('#update_chur').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_chur').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_chur').fadeOut('fast');
                    $("#stuch").val(data_id);
                    
                    if(data[10].length != 0){
                        $("#church").val(data[10][0].church);
                        $("#city").val(data[10][0].city);
                        $("#sector").val(data[10][0].sector);
                        $("#activities").val(data[10][0].activities);
                        
                        var selectElement1 = document.getElementById('countryc');
                        var selectElement2 = document.getElementById('licenced');
                        var selectElement3 = document.getElementById('ordained');
                        var selectElement4 = document.getElementById('minister');
                        
                        // Set selected values
                        var selectedOption1 = selectElement1.querySelector('option[value="' + data[10][0].country + '"]');
                        var selectedOption2 = selectElement2.querySelector('option[value="' + data[10][0].licenced + '"]');
                        var selectedOption3 = selectElement3.querySelector('option[value="' + data[10][0].ordained + '"]');
                        var selectedOption4 = selectElement4.querySelector('option[value="' + data[10][0].minister + '"]');
                        if(selectedOption1){
                            selectedOption1.selected = true;
                            selectElement1.prepend(selectedOption1);
                            
                        }
                        if(selectedOption2){
                            selectedOption2.selected = true;
                            selectElement2.prepend(selectedOption2);
                        }
                        if(selectedOption3){
                            selectedOption3.selected = true;
                            selectElement3.prepend(selectedOption3);
                        }
                        if(selectedOption4){
                            selectedOption4.selected = true;
                            selectElement4.prepend(selectedOption4);
                        }
                    }
                    $('#updateModal_chur').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View academics
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'load_academic_info'
                    };
            $('#spinner_e_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_e_'+data_id).fadeOut('fast');
                    $("#reg_prg_id").val(data_id);
                    $("#stu_reg_no").val(data[0].reg_no);
                    $("#fac_id").empty(); 
                    $("#dept_id").empty();
                    $("#splz_id").empty();
                    $("#level_id").empty();
                    $.each(data[1], function (index, value) {
                        $("#fac_id").append("<option value='" + value.fac_id + "'>" + value.fac_full_name +"</option>");
                    });
                    $.each(data[2], function (index, value) {
                        $("#dept_id").append("<option value='" + value.dept_id + "'>" + value.dept_full_name +"</option>");
                    });
                    $.each(data[3], function (index, value) {
                        $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +"</option>");
                    });
                    $.each(data[4], function (index, value) {
                        $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                    });
                        
                    // set select elements
                    var selectElement1 = document.getElementById('prg_type');
                    var selectElement2 = document.getElementById('fac_id');
                    var selectElement3 = document.getElementById('dept_id');
                    var selectElement4 = document.getElementById('splz_id');
                    var selectElement5 = document.getElementById('level_id');
                    var selectElement7 = document.getElementById('prg_mode_id');
                    var selectElement8 = document.getElementById('reg_active');
                    
                    // Set to be selected values
                    var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].prg_type + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].fac_id + '"]');
                    var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].dept_id + '"]');
                    var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].splz_id + '"]');
                    var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].level_id + '"]');
                    var selectedOption7 = selectElement7.querySelector('option[value="' + data[0].prg_mode_id + '"]');
                    var selectedOption8 = selectElement8.querySelector('option[value="' + data[0].reg_active + '"]');
                    if(selectedOption1){
                        selectedOption1.selected = true;
                        selectElement1.prepend(selectedOption1);
                    }
                    if(selectedOption2){
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    if(selectedOption3){
                        selectedOption3.selected = true;
                        selectElement3.prepend(selectedOption3);
                    }
                    if(selectedOption4){
                        selectedOption4.selected = true;
                        selectElement4.prepend(selectedOption4);
                    }
                    if(selectedOption5){
                        selectedOption5.selected = true;
                        selectElement5.prepend(selectedOption5);
                    }
                    if(selectedOption7){
                        selectedOption7.selected = true;
                        selectElement7.prepend(selectedOption7);
                    }
                    
                    if(selectedOption8){
                        selectedOption8.selected = true;
                        selectElement8.prepend(selectedOption8);
                    }
                    $('#updateModal_acc').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        
        //pre-update View sponsor
        $(document).on('click','.edits',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'load_sponsor_info'
                };
            $('#spinner_s_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_s_'+data_id).fadeOut('fast');
                    $("#reg_prg_ids").val(data_id);
                    $("#stu_reg_nos").val(data.reg_no);

                    var selectElement = document.getElementById('spon_id');
                    var selectedOption = selectElement.querySelector('option[value="' + data.spon_id + '"]');

                    if(selectedOption){
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }

                    $('#updateModal_spon').modal('show');
				},
				error:function(error){
				    $('#spinner_s_'+data_id).fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        
        //pre-update View passes
        $(document).on('click','.editp', function () {
            var data_id = $(this).data('id');
            var getData= {
                    course_id: data_id,
                    action:'load_pass_info'
                    };
            $('#spinner_f_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_f_'+data_id).fadeOut('fast');
                    $("#course_id").val(data_id);
                    $("#course").val(data.course);
                    $("#grade").val(data.grade);
                    $('#updateModal_pass').modal('show');
				},
				error:function(error){
				    $('#spinner_f_'+data_id).fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //Update Sponsors
        $("#update_form_spon").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner5_spon').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator5_spon').html("Saving...");
            $.ajax({
                url: "/files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner5_spon').fadeOut('fast');
                    $('#indicator5_spon').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_spon").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner5_spon').fadeOut('fast');
                    $('#indicator5_spon').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });

        //Update Personal
        $("#update_form_p").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_p').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_p").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //Update contact
        $("#update_form_c").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_c').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_c").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
              
        //Update address
        $("#update_form_a").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_a').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_a").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        
        //Update Church
        $("#update_form_chur").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_chur').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_chur').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_chur').fadeOut('fast');
                    $('#indicator2_chur').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_chur").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_chur').fadeOut('fast');
                    $('#indicator2_chur').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //update academics
        $("#update_form_acc").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner5_acc').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator5_acc').html("Saving...");
            $("#uacBtn").attr('disabled', true);
            $.ajax({
                url: "/files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner5_acc').fadeOut('fast');
                    $('#indicator5_acc').html("Save changes");
                    $("#uacBtn").removeAttr('disabled');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_acc").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#uacBtn").removeAttr('disabled');
                    $('#spinner5_acc').fadeOut('fast');
                    $('#indicator5_acc').html("Save");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //update passes
        $("#update_form_pass").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner10_pass').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator10_pass').html("Saving...");
            $("#upassBtn").attr('disabled', true);
            $.ajax({
                url: "/files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Save changes");
                    $("#upassBtn").removeAttr('disabled');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_pass").modal('hide');
                        load_info();
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#upassBtn").removeAttr('disabled');
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Save");
                    pop_wrong("Something went wrong!");
                }
            });
        });
          
        //load provinces
        $('#country').change(function () {
            if($("#country").val()==160){
                $("#province_id").attr('required', true);
                $("#district_id").attr('required', true);
                $("#sector").attr('required', true);
                $("#cell_id").attr('required', true);
                $("#village_id").attr('required', true);
                $("#uBtn").attr('hidden', true);
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
                
                var getData= {
                        action:'load_provinces'
                        };
                $('#spinner_cntr').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/application/application_controller.php",
                    data: getData,
                    dataType:"JSON",
                    success:function(data){
                        $('#spinner_cntr').fadeOut('fast');
                        $("#province_id").append("<option></option>")
                        $.each(data, function (index, value) {
                            $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                        });
                        $("#prov").attr('hidden',false);
    				},
    				error:function(error){
    				    $('#spinner_cntr').fadeOut('fast');
                        pop_wrong("Something went wrong!"); 
    				}
                });
            }
            else{
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#uBtn").attr('hidden', false);
                $("#province_id").attr('required', false);
                $("#district_id").attr('required', false);
                $("#sector").attr('required', false);
                $("#cell_id").attr('required', false);
                $("#village_id").attr('required', false);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
            }
        });

        //load districts
        $('#province_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    pid:$('#province_id').val(),
                    action:'load_districts'
                    };
            $("#district_id").empty();
            $('#spinner_prov').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_prov').fadeOut('fast');
                    $("#district_id").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict+"</option>");
                    });
                    $("#district").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_prov').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load sectors
        $('#district_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    did: $('#district_id').val(),
                    action:'load_sectors'
                    };
            $("#sector").empty();
            $('#spinner_dis').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_dis').fadeOut('fast');
                    $("#sector").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector+"</option>");
                    });
                    $("#sect").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_dis').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load cells
        $('#sector').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    sid: $('#sector').val(),
                    action:'load_cells'
                    };
            $("#cell_id").empty();
            $('#spinner_sect').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_sect').fadeOut('fast');
                    $("#cell_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell+"</option>");
                    });
                    $("#cell").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_sect').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load villages
        $('#cell_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    cid: $('#cell_id').val(),
                    action:'load_villages'
                    };
            $("#village_id").empty();
            $('#spinner_cell').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_cell').fadeOut('fast');
                    $.each(data, function (index, value) {
                        $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName+"</option>");
                    });
                    $("#village").attr('hidden',false);
                    $("#uBtn").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_cell').fadeOut('fast');
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
            $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
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

        //load departments
        $("#fac_id").change(function () {
            var fac_id = $("#fac_id").val();
            var formdata = {
                fac: fac_id,
                action: "load_departments"
            };
            $('#dept').css({'display':'none'});
            $('#spec').css({'display':'none'});
            $('#spinner000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Faculties/faculty_controller.php",
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


        //load specs
        $("#dept_id").change(function () {
            var dept_id = $("#dept_id").val();
            var formdata = {
                department: dept_id,
                action: "load_specs"
            };
            $('#spec').css({'display':'none'});
            $('#spinner0000').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Departments/department_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner0000').fadeOut('fast');
                    $('#spec').css({'display':'block'});
                    $("#splz_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#splz_id").append("<option value='" + value.splz_id + "'>" + value.splz_full_name +" ["+value.splz_short_name+"]</option>");
                        });
                   }
                   
                 },
                error:function(error){
                    $('#spinner0000').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                 
            });
        });

        //load levels
        $("#prg_type").change(function () {
            var type = $("#prg_type").val();
            var formdata = {
                type: type,
                action: "load_levels"
            };
             $('#spinner00').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Programs/program_controller.php",
                data: formdata,
                dataType: "JSON",
                success: function (data) {
                    $('#spinner00').fadeOut('fast');
                    $('#level').css({'display':'block'});
                    $("#level_id").empty();
                   if(data.length>0){
                        $.each(data, function (index, value) {
                            $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                        });
                   }
                 },
                error:function(error){
                    $('#spinner00').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                }
                  
            });
        });
        
        $(document).on('click', '#update_prev_edu', function(e){
            $("#stucodep").val($(this).data('id'));
            $("#updateModal_prevedu").modal('show');
        });
        
        $("#update_form_prev").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinner2_prev').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_prev').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinner2_prev').fadeOut('fast');
                    $('#indicator2_prev').html("Save");
                    pop_up_success(formData.message)
                    load_info();
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner2_prev').fadeOut('fast');
                    $('#indicator2_prev').html("Save");
                }
            });
        });
        
        $(document).on('click', '.deletedu', function(){
            var entry = $(this).data('id');
            var formdata = {
                id: entry,
                action: 'delete_education'
            }

            $('#spinner_edu_'+entry).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                success: function(formData){
                    $('#spinner_edu_'+entry).fadeOut('fast');
                    pop_up_success(formData.message)
                    load_info();
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinner_edu_'+entry).fadeOut('fast');
                }
            });
        });
        
        
        $("#acceptance").click(function(){
        	var student = $(this).data('id');
        	const pageURL = "/files/Student/acceptance?k="+student;
            var left = (screen.width - 800) / 2;
            var top = (screen.height - 600) / 4;
            window.open(pageURL, "Admission Letter", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=600, top=' + top + ', left=' + left);
        });
    });      
</script>