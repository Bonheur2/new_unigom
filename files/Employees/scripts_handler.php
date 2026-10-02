<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://unpkg.com/dropzone"></script>
<script src="https://unpkg.com/cropperjs"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
$(document).ready(function(){
    $('#employees_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
      });
          
//load provinces
        $('#country').change(function () {
            if($("#country").val()==160){
            $("#province_id").attr('required', true);
            $("#district_id").attr('required', true);
            $("#sector").attr('required', true);
            $("#cell_id").attr('required', true);
            $("#village_id").attr('required', true);
            $("#prov").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cellF").attr('hidden', true);
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
                $("#cellF").attr('hidden', true);
                $("#village").attr('hidden', true);
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
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cellF").attr('hidden', true);
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
            $("#sect").attr('hidden', true);
            $("#cellF").attr('hidden', true);
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
            $("#cellF").attr('hidden', true);
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
                    $("#cellF").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_sect').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
//load villages
        $('#cell_id').change(function () {
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

				},
				error:function(error){
				    $('#spinner_cell').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
//register new employee
    $("#register_employee").submit(function(e){
    e.preventDefault();

    var formData = new FormData(this);
    $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $('#indicator').html("Saving...");
    
    $.ajax({
        url: "/files/Employees/controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(data){
            $('#spinner').fadeOut('fast');
            $('#indicator').html("Save");
            
            if(data.status == 200){
                pop_up_success(data.message);
                $("#register_employee")[0].reset();
                $('#employees_table').load(location.href + " #employees_table");
                
                setTimeout(function() {
                    location.reload();
                }, 3000);
                
            } else {
                pop_wrong(data.message);
            }
        },
        error: function(xhr, status, error){
            $('#spinner').fadeOut('fast');
            $('#indicator').html("Save");
            pop_wrong("Error: " + error);
        }
    });
});
        
     
     
     
     
             $(document).on('click', '.reset', function() {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'force_reset_password'
                    };
            swal({
                title: "Are you sure?",
                text: "You are about to reset the password for this user!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner33_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/User/user_controller.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner33_'+data_id).fadeOut('fast');
                        if(data.status==401){
                            pop_info(data.message); 
                        }
                        else if(data.status==200){
                          pop_up_success(data.message); 
                          $('#users').load(location.href + " #users");
                        //   setTimeout(() => {
                        //     window.location.reload()}, 1000);
                            
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                
                        }
    				},
    				error:function(error){
    				    $('#spinner33_'+data_id).fadeOut('fast');
                        pop_wrong("Something went wrong");
    				}
                });
                }
               else {
                    swal("Operation cancelled!!");
                }
            });
        });
        
        
                $(document).on('click', '.del', function() {
            var data_id = $(this).data('id');
            
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
            swal({
                title: "Are you sure?",
                text: "You are about to change this user's status!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/User/user_controller.php",
                    data: getData,
                    dataType:"json",
                    success:function(data){
                        $('#spinner3_'+data_id).fadeOut('fast');
                        if(data.status==401){
                            pop_info(data.message); 
                        }
                        else if(data.status==200){
                          pop_up_success(data.message); 
                          $('#users').load(location.href + " #users");
                          setTimeout(() => {
                            window.location.reload()}, 1000);
                        }
    				},
    				error:function(error){
    				    $('#spinner3_'+data_id).fadeOut('fast');
                        pop_wrong("Something went wrong");
    				}
                });
                }
               else {
                    swal("Operation cancelled!!");
                }
            });
        });



   
    });
</script>
    <script>
    //next buttons
        function goToSection2() {
            var section1Valid = validateSection1();
            if (section1Valid) {
                $("#fname_star").html("");
                $("#lname_star").html("");
                $("#nid_star").html("");
                $("#nat_star").html("");
                $("#gender_star").html("");
                $("#pname_star").html("");
                $("#mname_star").html("");
                $('#spinner1-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator1-1').html("loading...");
                    setTimeout(function() {
                        $('#spinner1-1').fadeOut('fast');
                        $('#indicator1-1').html("Next");
                        $("#section-1-indicator").removeClass('btn-primary');
                        $("#section-1-indicator").addClass('btn-light');
                        $("#section-2-indicator").removeClass('btn-light');
                        $("#section-2-indicator").addClass('btn-primary');
                        $('#section-1').attr('hidden',true);
                        $('#section-2').attr('hidden',false);
                        }, 500);
            }

        }
        
        function goToSection3() {
            var section2Valid = validateSection2();
            if (section2Valid) {
                $("#prov_star").html("");
                $("#dis_star").html("");
                $("#sec_star").html("");
                $("#cel_star").html("");
                $("#vil_star").html("");
                $('#spinner2-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator2-1').html("loading...");
                    setTimeout(function() {
                        $('#spinner2-1').fadeOut('fast');
                        $('#indicator2-1').html("Next");
                        $("#section-2-indicator").removeClass('btn-primary');
                        $("#section-2-indicator").addClass('btn-light');
                        $("#section-3-indicator").removeClass('btn-light');
                        $("#section-3-indicator").addClass('btn-primary');
                        $('#section-2').attr('hidden',true);
                        $('#section-3').attr('hidden',false);
                        }, 500);
            }
        }

        function goToSection4() {
            var section3Valid = validateSection3();
            if (section3Valid) {
                $("#email_star").html("");
                $("#phone_star").html("");
                $('#spinner3-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator3-1').html("loading...");
                    setTimeout(function() {
                        $('#spinner3-1').fadeOut('fast');
                        $('#indicator3-1').html("Next");
                        $("#section-3-indicator").removeClass('btn-primary');
                        $("#section-3-indicator").addClass('btn-light');
                        $("#section-4-indicator").removeClass('btn-light');
                        $("#section-4-indicator").addClass('btn-primary');
                        $('#section-3').attr('hidden',true);
                        $('#section-4').attr('hidden',false);
                        }, 500);
            }
        }
        
        function goToSection5() {
            var section4Valid = validateSection4();
            if (section4Valid) {
                $("#camp_star").html("");
                $("#prg_star").html("");
                $("#fac_star").html("");
                $("#dept_star").html("");
                $('#spinner4-1').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator4-1').html("loading...");
                    setTimeout(function() {
                        $('#spinner4-1').fadeOut('fast');
                        $('#indicator4-1').html("Next");
                        $("#section-4-indicator").removeClass('btn-primary');
                        $("#section-4-indicator").addClass('btn-light');
                        $("#section-5-indicator").removeClass('btn-light');
                        $("#section-5-indicator").addClass('btn-primary');
                        $('#section-4').attr('hidden',true);
                        $('#section-5').attr('hidden',false);
                        }, 500);
            }

        }
        
        //back buttons
        function goBackToSection1() {
            $("#section-2-indicator").removeClass('btn-primary');
            $("#section-2-indicator").addClass('btn-light');
            $("#section-1-indicator").removeClass('btn-light');
            $("#section-1-indicator").addClass('btn-primary');
            $('#section-2').attr('hidden',true);
            $('#section-1').attr('hidden',false);
        }
        
        function goBackToSection2() {
            $("#section-3-indicator").removeClass('btn-primary');
            $("#section-3-indicator").addClass('btn-light');
            $("#section-2-indicator").removeClass('btn-light');
            $("#section-2-indicator").addClass('btn-primary');
            $('#section-3').attr('hidden',true);
            $('#section-2').attr('hidden',false);
        }
        
        function goBackToSection3() {
            $("#section-4-indicator").removeClass('btn-primary');
            $("#section-4-indicator").addClass('btn-light');
            $("#section-3-indicator").removeClass('btn-light');
            $("#section-3-indicator").addClass('btn-primary');
            $('#section-4').attr('hidden',true);
            $('#section-3').attr('hidden',false);
        }
        
        function goBackToSection4() {
            $("#section-5-indicator").removeClass('btn-primary');
            $("#section-5-indicator").addClass('btn-light');
            $("#section-4-indicator").removeClass('btn-light');
            $("#section-4-indicator").addClass('btn-primary');
            $('#section-5').attr('hidden',true);
            $('#section-4').attr('hidden',false);
        }
        
        //validations
        function validateSection1() {
            var fname = document.getElementById('fname').value.trim();
            var lname = document.getElementById('lname').value.trim();
            var nid = document.getElementById('nid').value.trim();
            var nat = document.getElementById('nationality').value.trim();
            var gender = document.getElementById('gender').value.trim();
            var pname = document.getElementById('father_names').value.trim();
            var mname = document.getElementById('mother_names').value.trim();

            if (fname === '' || lname === '' || nid === '' || gender === '' || nat==='' || pname === '' || mname === '') {
                pop_wrong("Fill all missing fields"); 
                fname===''?$("#fname_star").html("*"):$("#fname_star").html("");
                lname===''?$("#lname_star").html("*"):$("#lname_star").html("");
                nid===''?$("#nid_star").html("*"):$("#nid_star").html("");
                nat===''?$("#nat_star").html("*"):$("#nat_star").html("");
                gender===''?$("#gender_star").html("*"):$("#gender_star").html("");
                pname===''?$("#pname_star").html("*"):$("#pname_star").html("");
                mname===''?$("#mname_star").html("*"):$("#mname_star").html("");
                return false;
            }

            return true;
        }
        function validateSection2() {
            var cntr = document.getElementById('country').value;
            var prov = document.getElementById('province_id').value;
            var dis = document.getElementById('district_id').value;
            var sec = document.getElementById('sector').value;
            var cel = document.getElementById('cell_id').value;
            var vil = document.getElementById('village_id').value;
            if(cntr === '160'){
                if (prov === '' || dis === '' || sec === '' || cel==='' || vil === '') {
                    pop_wrong("Fill all missing fields"); 
                    prov===''?$("#prov_star").html("*"):$("#prov_star").html("");
                    dis===''?$("#dis_star").html("*"):$("#dis_star").html("");
                    sec===''?$("#sec_star").html("*"):$("#sec_star").html("");
                    cel===''?$("#cel_star").html("*"):$("#cel_star").html("");
                    vil===''?$("#vil_star").html("*"):$("#vil_star").html("");
                    return false;
                }
            }
            return true;
        }
        function validateSection3() {
            // var email = document.getElementById('email').value.trim();
            // var phone = document.getElementById('phone').value.trim();
            // if (email === '' || phone === '') {
            //     pop_wrong("Fields with * are required"); 
            //     email===''?$("#email_star").html("*"):$("#email_star").html("");
            //     phone===''?$("#phone_star").html("*"):$("#phone_star").html("");
            //     return false;
            // }
            return true;
        }
        function validateSection4() {
            // var dep = document.getElementById('dep_id').value.trim();
            // var post = document.getElementById('post_id').value.trim();
            // if (post === '') {
            //     pop_wrong("All fields are required.");
            //     post===''?$("#pos_star").html("*"):$("#pos_star").html("");
                
            //     return false;
            // }
            return true;
        }
    </script>


<script>
   $(document).ready(function() {
       
       
       
       
    $('#School').change(function() {
        var schoolId = $(this).val();
        
        if(schoolId) {
            $('#spinner30').html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            $('#Departmentin').empty().append('<option value="">Loading departments...</option>').prop('disabled', false);
            $('#Departmentfld').show();

            $.ajax({
                url: '/files/Employees/getfiles/get_departments.php',
                type: 'POST',
                data: {school_id: schoolId},
                dataType: 'json',
                success: function(data) {
                    $('#Departmentin').empty();
                    if(data.length > 0) {
                        $('#Departmentin').append('<option value="">-- Select Department --</option>');
                        $.each(data, function(key, value) {
                            $('#Departmentin').append('<option value="'+ value.dept_id +'">'+ value.dept_full_name +'</option>');
                        });
                    } else {
                        $('#Departmentin').append('<option value="">No departments found</option>');
                    }
                    // Hide spinner
                    $('#spinner30').html('');
                },
                error: function() {
                    $('#Departmentin').empty().append('<option value="">Error loading departments</option>');
                    $('#spinner30').html('');
                }
            });
        } else {
            $('#Departmentin').empty().append('<option value="">-- Select School First --</option>').prop('disabled', true);
        }
    });





       
       $('#camps_id').change(function(e) {
        e.preventDefault();
        $('#type_form').css({
            display: 'block'
        });
        
        $('#accademic').change(function() {
            var staffCategoryId = $(this).val();
            var schoolDropdown = $('#School');
            var schoolForm = $('#Schoolform');
            
            if (staffCategoryId == 2) { 
                schoolDropdown.val('').trigger('change');
                schoolDropdown.prop('disabled', true);
                schoolDropdown.html('<option value="">Not applicable for Academic staff</option>');
                schoolForm.hide();
            } else {
               
                schoolForm.show();
                schoolDropdown.prop('disabled', false);
                schoolDropdown.html('<option value="">-- Select Campus First --</option>');
            
                if ($('#camps_id').val()) {
                    $('#camps_id').trigger('change');
                }
            }
        });
        
        var campusId = $(this).val();
        var schoolDropdown = $('#School');
        
        if (campusId) {
            // Disable school dropdown and show loading
            schoolDropdown.prop('disabled', true);
            schoolDropdown.html('<option value="">Loading schools...</option>');
            
            // AJAX request to fetch schools for the selected campus
            $.ajax({
                url: '/files/Employees/getfiles/get_schools.php',
                type: 'POST',
                data: { campus_id: campusId },
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        var options = '<option value=""></option>';
                        $.each(data, function(key, school) {
                            options += '<option value="' + school.fac_id + '">' + school.fac_full_name + '</option>';
                        });
                        schoolDropdown.html(options);
                    } else {
                        schoolDropdown.html('<option value="">No schools found for this campus</option>');
                    }
                    schoolDropdown.prop('disabled', false);
                },
                error: function() {
                    schoolDropdown.html('<option value="">Error loading schools</option>');
                    schoolDropdown.prop('disabled', false);
                }
            });
        } else {
            schoolDropdown.html('<option value="">-- Select Campus First --</option>');
            schoolDropdown.prop('disabled', true);
        }
        
        
    });
    



        

    
    
    
    
    
    $('#accademic').change(function(e) {
    e.preventDefault();
    var selectedType = $(this).val();
    
   
    if (selectedType != 1000000) {
        $('#dep_form').css({
            display: 'block'
        });
        
    } else {
        $('#dep_form').css({
            display: 'none'
        });
        $('#dep_id').val('').trigger('change');
    }
});
    
    $('#accademic').change(function(e) {
        e.preventDefault();
        $('#grade_id').css({
            display: 'block'
        });
        // $('#QualificationsF').css({
        //     display: 'block'
        // });
        // $('#DepartmentUntF').css({
        //     display: 'block'
        // });
        
        
        
    });
    $('#rc2probation_range').change(function(e) {
        e.preventDefault();
        // $('#grade_id').css({
        //     display: 'block'
        // });
        $('#QualificationsF').css({
            display: 'block'
        });
        $('#DepartmentUntF').css({
            display: 'block'
        });
        
        
        
    });
    
    // add here...
    $('#ac_grade_id').change(function(e) {
        e.preventDefault();
        $('#pst_id').css({
            display: 'block'
        });
        $('#part_full_form').css({
            display: 'block'
        });
    });
    $('#post_id').change(function(e) {
        e.preventDefault();
        $('#part_full_form').css({
            display: 'block'
        });
    });
    $('#part_full').change(function(e) {
        e.preventDefault();
        $('#probition_form').css({
            display: 'block'
        });
        $('#start_date_form').css({
            display: 'block'
        });
        $('#frole_id').css({
            display: 'block'
        });
        
        
    });
    $('#probiton').change(function(e) {
        e.preventDefault();
        $('#start_date_form').css({
            display: 'block'
        });
    });
    
    $('#probiton').change(function(e) {
        e.preventDefault();
        $('#frole_id').css({
            display: 'block'
        });
        $('#probation_form').css({
            display: 'block'
        });
    });
    $('#frole_id').change(function(e) {
        e.preventDefault();
        $('#fSupervisor').css({
            display: 'block'
        });
        $('#probation_form').css({
            display: 'block'
        });
    });
    // $('#fSupervisor').change(function(e) {
    //     e.preventDefault();
    //     $('#PSupervisor').css({
    //         display: 'block'
    //     });
    // });
    
    

// $(document).ready(function() {
    $('#fSupervisor_id').change(function() {
        var selectedPost = $(this).val();

        if (selectedPost != "") {
            // Show Supervisor select box
            $('#PSupervisor').show();

            // Fetch supervisors based on selected position
            $.ajax({
                url: '/files/Employees/get_supervisors.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#PSupervisor_id').html(response);
                    $('#spinner33').html(""); // remove spinner
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#PSupervisor').hide();
            $('#PSupervisor_id').html('<option value=""></option>');
        }
    });
// });


    
    $('#bank_name').on('input', function() {
    if ($(this).val().trim() !== '') {
      $('#Acc_id').css('display', 'block');
    } else {
      $('#Acc_id').css('display', 'none');
    }
  });
    $('#acc_number').on('input',function(){
        
     if($(this).val().trim() !=''){
      $('#sal_id').css('display', 'block');   
      $('#ScaleF').css('display', 'block');   
     } 
     else{
         $('#sal_id').css('display', 'none');
         $('#ScaleF').css('display', 'none');
     }
     
    });
    // buttons handler
   $(document).on('click', '.view-employee', function(e) {
    var spinner = $(this).find('.spinner80'); // Find the spinner within the clicked button
    spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    e.preventDefault();
    var value = $(this).data('emp-id'); // Use .data('emp-id') to access the emp_id value
    var formData = {
        emp_id: value,
        action: "view_employee"
    };
    $.ajax({
      url: "/files/Employees/controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data){
      spinner.fadeOut('fast');
      $('.spinner15').fadeOut('fast');
     $('#fisrt_name').html(data[0].family_name);
     $('#last_name').html(data[0].first_name);
     $('#idn_view').html(data[0].nid);
     var gender = data[0].gender;
       if (gender === "M") {
          $('#gender_view').html("Male");
       } else if (gender === "F") {
          $('#gender_view').html("Female");
       } else {
          $('#gender_view').html("Unknown");
       }
       var martialStatus = data[0].marital_status	;
       if (martialStatus === "Single") {
          $('#martial_view').html("Single");
       } else if (martialStatus === "Married") {
          $('#martial_view').html("Married");
       }
       else if (martialStatus === "Widowed") {
          $('#martial_view').html("Widowed");
       }
       else if (martialStatus === "Divorced") {
          $('#martial_view').html("Divorced");
       }
       else {
          $('#martial_view').html("Unknown");
       }
     $('#dob_view').html(data[0].dob);
     $('#nationality_view').html(data[0].nationality);
     $('#father_view').html(data[0].father_name);
     $('#mother_view').html(data[0].mother_name);
     $('#QualificationsView').html(data[0].qualifications);
     $('#DepartmentuView').html(data[0].deptunt);
     
     $('#residence_view').html(data[0].cntr_name);
     $('#province_view').html(data[0].provincename);
     $('#district_view').html(data[0].namedistrict);
     $('#sector_view').html(data[0].namesector);
     $('#cell_view').html(data[0].nameCell);
     $('#village_view').html(data[0].VillageName);
     $('#phone_view').html(data[0].phone);
     $('#email_view').html(data[0].email);
     $('#campus_view').html(data[0].camp_full_name);
     $('#School_view').html(data[0].fac_full_name);
     $('#Department_vieww').html(data[0].dept_full_name);
     
     $('#department_view').html(data[1].dept_full_name);
     $('#accademic_view').html(data[0].staff_type_full_name);
     $('#grade_view').html(data[1].acad_grad_full_name	);
      var probition = data[1].probation_period;
        if (probition ==3) {
           $('#probition_view').html("3 Months");
        } else if (probition ==6) {
           $('#probition_view').html("6 Months");
        }  
     $('#start_job_view').html(data[1].join_date);
     $('#part_full_view').html(data[0].contr_name);
     $('#post_view').html(data[1].staff_post_full_name);
     $('#bank_view').html(data[0].bank);
     $('#acc_view').html(data[0].acc_no);
     $('#salary_view').html(data[0].gross_salary);    
     $('#ScaleofSalaryView').html(data[0].ScaleofSalary);    
     
     
     var imge = data[0].staff_image // Assuming the value of `data.staff_image` is the name of the image file
  
    // var imagePath = "/staff_docs/";
    var imagePath = "";
    var imageUrl = imagePath + imge;
    
    var imgElement = document.querySelector('.profile-widget-picture');
    imgElement.src = imageUrl;
     
     $('#viewModal').modal('show');
       },error: function(){
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
    });
});

$(document).on('click', '.edit-employee', function(e) {
  e.preventDefault();
    var spinner = $(this).find('.spinner81'); // Find the spinner within the clicked button
    spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    e.preventDefault();
    var value = $(this).data('emp-id'); // Use .data('emp-id') to access the emp_id value
   var formData={
     emp_id:value,
     action:"edit_employee"
    };
    $.ajax({
      url: "/files/Employees/controller.php",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data){
        spinner.fadeOut('fast');
    $('#lname_edit').val(data[0].family_name);
    $('#fname_edit').val(data[0].first_name);
    $('#idn_edit').val(data[0].nid);
    var gender = data[0].gender;
    var selectElement = $("#gender_edit");
    selectElement.val(gender);
    selectElement.prepend(selectElement.find("option[value='" + gender + "']"));
    
    
    var martial = data[0].marital_status;
    var selectMartial = $("#martial_edit");
    selectMartial.val(martial);
    selectMartial.prepend(selectMartial.find("option[value='" + martial + "']"));
    
    $('#dob_edit').val(data[0].dob);
    
    var nationality = data[0].nationality;
    var selectElement = $("#nationality_edit");
    selectElement.val(nationality);
    selectElement.prepend(selectElement.find("option[value='" + nationality + "']"));
    
      $('#father_edit').val(data[0].father_name);
      $('#mother_edit').val(data[0].mother_name);
      $('#rssb_edit').val(data[0].rssb);
       var residence = data[0].country;
       var selectElement = $("#residence_edit");
       selectElement.val(residence);
      selectElement.prepend(selectElement.find("option[value='" + residence + "']"));
   
     var province = data[0].province;
       var selectElement = $("#province_edit");
       selectElement.val(province);
      selectElement.prepend(selectElement.find("option[value='" + province + "']"));
      
     var district = data[0].district;
       var selectElement = $("#district_edit");
       selectElement.val(district);
      selectElement.prepend(selectElement.find("option[value='" + district + "']"));
      
      var sectors = data[0].sector;
       var selectElement = $("#sector_edit");
       selectElement.val(sectors);
      selectElement.prepend(selectElement.find("option[value='" + sectors + "']"));
      
      var cell = data[0].cell;
      var selectElement = $("#cell_edit");
      selectElement.val(cell);
      selectElement.prepend(selectElement.find("option[value='" + cell + "']"));
      
      var village = data[0].village;
       var selectElement = $("#village_edit");
       selectElement.val(village);
      selectElement.prepend(selectElement.find("option[value='" + village + "']"));
      
      $("#phone_edit").val(data[0].phone);
      $("#email_edit").val(data[0].email);
      
      var campus=data[0].campus;
      var selectElement = $("#campus_edit");
       selectElement.val(campus);
      selectElement.prepend(selectElement.find("option[value='" + campus + "']"));
      
      var department=data[1].department;
      var selectElement = $("#department_edit");
       selectElement.val(department);
      selectElement.prepend(selectElement.find("option[value='" + department + "']"));
     
    var accademic = data[1].is_acadmic;
    var selectAccademic = $("#accademic_edit");
    selectAccademic.val(accademic);
    selectAccademic.prepend(selectAccademic.find("option[value='" + accademic + "']"));
    
    var grades = data[1].acad_grad_id;
    var selectGrades = $("#acc_grade_edit");
    selectGrades.val(grades);
    selectGrades.prepend(selectGrades.find("option[value='" + grades + "']"));
    
    var probition = data[1].probation_period;
    var selectProbition = $("#probition_edit");
    selectProbition.val(probition);
    selectProbition.prepend(selectProbition.find("option[value='" + probition + "']")); 
     
    $('#start_date_edit').val(data[1].join_date);
      
    var partfull = data[1].contract;
    var selectPartFull = $("#part_full_edit");
    selectPartFull.val(partfull);
    selectPartFull.prepend(selectPartFull.find("option[value='" + partfull + "']")); 
     
    var post = data[1].post;
    var selectPostFull = $("#post_edit");
    selectPostFull.val(post);
    selectPostFull.prepend(selectPostFull.find("option[value='" + post + "']")); 
      
      
      $("#bank_edit").val(data[0].bank);
      $("#account_edit").val(data[0].acc_no);
      $("#salary_edit").val(data[1].basic_salary);
      $("#staff_id").val(data[0].staff_id);
   $('#edit_card').css({
         display:'block'
     });
       },error: function(){
                            spinner.fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
    });
});


// editform load province

$('#residence_edit').change(function () {
           var getData= {
              c_id:$('#residence_edit').val(),
              action:'load_provinces'
                    };
            $('#spinner21').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Employees/controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner21').fadeOut('fast');
                    $("#province_edit").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#province_edit").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                    });
				},
				error:function(error){
				    $('#spinner21').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
            });
            
      $('#province_edit').change(function () {
        var getData = {
            p_id: $('#province_edit').val(),
            action: 'load_districts'
        };
        $('#spinner22').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner22').fadeOut('fast');
                var districtSelect = $('#district_edit');
                districtSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    districtSelect.append("<option value='" + value.districtcode + "'>" + value.namedistrict + "</option>");
                });
            },
            error: function (error) {
                $('#spinner22').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });

    $('#district_edit').change(function () {
        var getData = {
            d_id: $('#district_edit').val(),
            action: 'load_sectors'
        };
        $('#spinner23').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner23').fadeOut('fast');
                var sectorSelect = $('#sector_edit');
                sectorSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    sectorSelect.append("<option value='" + value.sectorcode + "'>" + value.namesector + "</option>");
                });
            },
            error: function (error) {
                $('#spinner23').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $('#sector_edit').change(function () {
        var getData = {
            s_id: $('#sector_edit').val(),
            action: 'load_cells'
        };
        $('#spinner24').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner24').fadeOut('fast');
                var cellSelect = $('#cell_edit');
                cellSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    cellSelect.append("<option value='" + value.codecell + "'>" + value.nameCell + "</option>");
                });
            },
            error: function (error) {
                $('#spinner24').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $('#cell_edit').change(function () {
        var getData = {
            cell_id_code: $('#cell_edit').val(),
            action: 'load_villages'
        };
        $('#spinner25').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Employees/controller.php",
            data: getData,
            dataType: "JSON",
            success: function (data) {
                $('#spinner25').fadeOut('fast');
                var villageSelect = $('#village_edit');
                villageSelect.empty(); // Clear existing options
                $.each(data, function (index, value) {
                    villageSelect.append("<option value='" + value.CodeVillage + "'>" + value.VillageName + "</option>");
                });
            },
            error: function (error) {
                $('#spinner25').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
 $(document).on('click','.delete-employee',function (e) {
    var spinner = $(this).find('.spinner82'); // Find the spinner within the clicked button
    
    e.preventDefault();
    var value = $(this).data('emp-id'); 
   var formData={
        staff_id:value,
        action:"delete_employee"
       };
            swal({
            title: "Are you sure?",
            text: "You want to delete this staff",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "/files/Employees/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
               if(data.status==200){
                   spinner.fadeOut('fast');
                   pop_wrong(data.message);
                   $('#employees_table').load(location.href + " #employees_table");
               }
               if(data.status==500){
                   pop_wrong(data.message);
                   spinner.fadeOut('fast');
                   
               }
            }
            });
            }
           else {
                swal("operation Cancelled!!");
            }
        });
        });


$("#edit_employee_card").submit(function(e) {
    e.preventDefault();
    $('.spinner81').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    
    // Create FormData and add custom fields
    var formData = new FormData(this);
    
    // Add debugging
    console.log("Form data being sent:");
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    $.ajax({
        type: "POST",
        url: "/files/Employees/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: function(data) {
            $('.spinner81').fadeOut('fast');
            console.log("Server response:", data);
            
            if (data.status == 200) {
                
                pop_up_success(data.message);
                    $('#employees_table').load(location.href + " #employees_table");
                    $('#editEmployeeModal').modal('hide');
                    setTimeout(function() {
                                location.reload();
                            }, 3000);
            } else {
                pop_wrong(data.message);
            }
        },
        error: function(xhr, status, error) {
            $('.spinner81').fadeOut('fast');
            console.error("AJAX Error:", status, error);
            pop_wrong("Update failed: " + error);
        }
    });
});


// classes oof add primary and light on edit form

// $('.second').on('click', function(e) {
//   e.preventDefault();
//   $(".second").removeClass('btn-light');
//  $(".second").addClass('btn-primary');
//  $(".firstly").removeClass('btn-primary');
//  $(".firstly").addClass('btn-light');
// });
// $('.third').on('click', function(e) {
//   e.preventDefault();
//   $(".third").removeClass('btn-light');
//  $(".third").addClass('btn-primary');
//   $(".second").removeClass('btn-primary');
//  $(".second").addClass('btn-light');
//  $(".firstly").removeClass('btn-primary');
//  $(".firstly").addClass('btn-light');
// });
// $('.fourth').on('click', function(e) {
//   e.preventDefault();
//   $(".fourth").removeClass('btn-light');
//  $(".fourth").addClass('btn-primary');
//   $(".third").removeClass('btn-primary');
//  $(".third").addClass('btn-light');
// });
// $('.fifth').on('click', function(e) {
//   e.preventDefault();
//   $(".fifth").removeClass('btn-light');
//  $(".fifth").addClass('btn-primary');
//   $(".fourth").removeClass('btn-primary');
//  $(".fourth").addClass('btn-light');
// });
 
 	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;
       var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 1,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
	    $('#spinner35').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});
        const fileImage = document.getElementById('upload_image');
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file

                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"upload_image");
				$.ajax({
					url:'/files/Employees/controller.php',
					method:'POST',
					dataType:"JSON",
					data:formData,
					processData: false,
                    contentType: false,
					success:function(data)
					{
					    $('#spinner35').fadeOut('fast');
					    $modal.modal('hide');
					}
				});
			};
		});
	});


});
</script>
<script>
    $(document).ready(function(){
    var $modal = $('#modal_edit');
  var image = document.getElementById('sample_image_edit');

	var cropper;

	$('#upload_image_edit').change(function(event){
		var files = event.target.files;
       var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 1,
			preview:'.preview_edit'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop_edit').click(function(){
	    $('#spinner84').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});
        const fileImage = document.getElementById('upload_image_edit');
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
                        var filename = fileImage.files[0].name; // Retrieve the name of the file
                         var staff_id=$('#staff_id').val();
                        var formData = new FormData(); // Create a new FormData object
                        formData.append('image', base64data);
                        formData.append('nameoffile', filename);
                        formData.append('action',"upload_image_edit");
				$.ajax({
					url:'/files/Employees/controller.php',
					method:'POST',
					dataType:"JSON",
					data:formData,
					processData: false,
                    contentType: false,
					success:function(data)
					{
					    $('#spinner84').fadeOut('fast');
					    $modal.modal('hide');
					}
				});
			};
		});
	});
 
    })
</script>