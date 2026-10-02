<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h3>4. Family & Sponsor</h3>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Previous Educaction</a></div>
                    </div>
                </div>
                <?php FamilySponsor($conn, $code); ?>
                <div class="section-body">
                    <div class="row" id="profile">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">Parent/Guardian Details</h4>
                                    </div>
                                    <div>
                                        <button class="btn btn-info me-2" id="add_family">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                        <a data-collapse="#mycard-collapse27" class="btn btn-icon btn-info" href="#">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="collapse show" id="mycard-collapse27">
                                    <div class="card-body">
                                                    <?php
                                                $select_prev = "SELECT *
                                                                FROM applicant_family_sponsor 
                                                                
                                                                WHERE Stu_code = :code";
                                                
                                                $cselect_prev = $conn->prepare($select_prev);
                                                $cselect_prev->bindParam(':code', $code, PDO::PARAM_STR);
                                                $cselect_prev->execute(); 
                                                
                                                $row_cselect_prev = $cselect_prev->fetch(PDO::FETCH_ASSOC);
                                                if($row_cselect_prev){
                                                ?>
                                                
                                                <div class="">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            
                                                                <tr>
                                                                    <th scope="col">First Name:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['first_name']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Surname:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['last_name']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Relationship:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Relationship']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Education:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Education']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Occupation:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Occupation']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Address:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Address']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Phone:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Phone']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Email:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['Email']); ?></th>
                                                                </tr>
                                                                
                                                            
                                                                
                                                           
                                                        </thead>
                                                    </table>
                                    </div>
                                    <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                        <button class="btn btn-primary btn-sm" id="update_family" data-id="<?php echo $row_cselect_prev['fs_id']; ?>"><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                                    </div>
                                    <?php
                                    }
                                    else{}
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">Sponsorship Details</h4>
                                    </div>
                                    <div>
                                        <button class="btn btn-info me-2" id="add_sponsor">
                                            <i class="fas fa-plus me-1"></i> Add
                                        </button>
                                        <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="collapse show" id="mycard-collapse2">
                                    <div class="card-body">
                                                    <?php
                                                $select_prev = "SELECT *
                                                                FROM applicant_family_sponsor 
                                                                
                                                                WHERE Stu_code = :code";
                                                
                                                $cselect_prev = $conn->prepare($select_prev);
                                                $cselect_prev->bindParam(':code', $code, PDO::PARAM_STR);
                                                $cselect_prev->execute(); 
                                                
                                                $row_cselect_prev = $cselect_prev->fetch(PDO::FETCH_ASSOC);
                                                if($row_cselect_prev){
                                                ?>
                                                
                                                <div class="">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            
                                                                <tr>
                                                                    <th scope="col">First Name:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['s_first_name']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Surname:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['s_last_name']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Telephone:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['s_Phone']); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col">Address:</th>
                                                                    <th scope="col"><?php echo htmlspecialchars($row_cselect_prev['s_Address']); ?></th>
                                                                </tr>
                                                                
                                                            
                                                                
                                                           
                                                        </thead>
                                                    </table>
                                    </div>
                                    <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                        <button class="btn btn-primary btn-sm" id="update_sponsor" data-id="<?php echo $row_cselect_prev['fs_id']; ?>"><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                                    </div>
                                    <?php
                                    }
                                    else{}
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php ReportProblem($conn, $code, $thing); ?>
                    </div>
                </div>
                
            </section>
        </div>
        
        <!--update modal contact-->
                        <form  method="POST" id="add_familly">
                        <div class="modal fade" tabindex="-1" role="dialog" id="addModal_family">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Parent/Guardian Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="add_familly">
                                        <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                    
                                        <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="first_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surname</label>
                                                <input type="text" name="last_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Relationship</label>
                                                <input type="text" name="Relationship" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Education</label>
                                                <input type="text" name="Education" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <input type="text" name="Occupation" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="Address" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="Phone" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="Email" class="form-control">
                                            </div>
                    
                                    </div>
                                    <div class="modal-footer bg-whitesmoke">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="e_appBtn">
                                            <span id="spinne1rF"></span>&nbsp;<span id="indicatorF">Save changes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        <form  method="POST" id="update_familly">
                        <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_family">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Parent/Guardian Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update_familys">
                                        <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                                        <input type="hidden" name="fs_id" id="fs_id">
                    
                                        <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="first_name" id="first_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surname</label>
                                                <input type="text" name="last_name" id="last_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Relationship</label>
                                                <input type="text" name="Relationship" id="Relationship" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Education</label>
                                                <input type="text" name="Education" id="Education" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <input type="text" name="Occupation" id="Occupation" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="Address" id="Address" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="Phone" id="Phone" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="Email" id="Email" class="form-control">
                                            </div>
                    
                                    </div>
                                    <div class="modal-footer bg-whitesmoke">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="e_appBtn">
                                            <span id="spinne1rFu"></span>&nbsp;<span id="indicatorFu">Save changes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        <form  method="POST" id="add_sponsor_form">
                        <div class="modal fade" tabindex="-1" role="dialog" id="addModal_sponsor">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add sponsorship Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="add_sponsors">
                                        <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                    
                                        <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="s_first_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surname</label>
                                                <input type="text" name="s_last_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="s_Address" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="s_Phone" class="form-control">
                                            </div>

                    
                                    </div>
                                    <div class="modal-footer bg-whitesmoke">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="e_appBtn">
                                            <span id="spinne1rs"></span>&nbsp;<span id="indicators">Save changes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        
                        <form  method="POST" id="update_sponsor_form">
                        <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_sponsor">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update sponsorship Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="update_sponsors">
                                        <input type="hidden" name="Stu_code" value="<?php echo $code; ?>">
                    
                                        <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="s_first_name" id="s_first_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Surname</label>
                                                <input type="text" name="s_last_name" id="s_last_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="s_Address" id="s_Address" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" name="s_Phone" id="s_Phone" class="form-control">
                                            </div>

                    
                                    </div>
                                    <div class="modal-footer bg-whitesmoke">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="e_appBtn">
                                            <span id="spinne1rsu"></span>&nbsp;<span id="indicatorsu">Save changes</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        
    

        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


<script>
    $(document).ready(function(){
        $('#add_family').click(function(){
            $('#addModal_family').modal('show');
            
        });
        $('#add_sponsor').click(function(){
            $('#addModal_sponsor').modal('show');
            
        });
         $("#add_familly").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinne1rF').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicatorF').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinne1rF').fadeOut('fast');
                    $('#indicatorF').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinne1rF').fadeOut('fast');
                    $('#indicatorF').html("Save");
                }
            });
        });
        
        $('#update_family').click(function () {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'show_family'
        };
        $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: getData,
            dataType:"JSON",
            success:function(data){
                console.log(data);
                // $('#spinner_c').fadeOut('fast');
                $("#fs_id").val(data_id);
                $("#Stu_code").val(data[0].prev_code);
                $("#first_name").val(data[0].first_name);
                $("#last_name").val(data[0].last_name);
                $("#Relationship").val(data[0].Relationship);    
                $("#Education").val(data[0].Education);
                $("#Occupation").val(data[0].Occupation);    
                $("#Address").val(data[0].Address);
                $("#Phone").val(data[0].Phone);
                $("#Email").val(data[0].Email);    
                
               

                
                $('#updateModal_family').modal('show');
                
			},
			error:function(error){
			    $('#spinner_c').fadeOut('fast');
			    pop_wrong("Something went wrong!");
			}
        });
    });
    $("#update_familly").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinne1rFu').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicatorFu').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinne1rFu').fadeOut('fast');
                    $('#indicatorFu').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinne1rFu').fadeOut('fast');
                    $('#indicatorFu').html("Save");
                }
            });
        });
        
        $("#add_sponsor_form").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinne1rs').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicators').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinne1rF').fadeOut('fast');
                    $('#indicatorF').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinne1rs').fadeOut('fast');
                    $('#indicators').html("Save");
                }
            });
        });
        
        $('#update_sponsor').click(function () {
        var data_id = $(this).data('id');
        var getData = {
            id: data_id,
            action: 'show_family'
        };
        $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: getData,
            dataType:"JSON",
            success:function(data){
                console.log(data);
                // $('#spinner_c').fadeOut('fast');
                $("#fs_id").val(data_id);
                $("#Stu_code").val(data[0].prev_code);
                $("#s_first_name").val(data[0].s_first_name);
                $("#s_last_name").val(data[0].s_last_name);
                $("#s_Phone").val(data[0].s_Phone);    
                $("#s_Address").val(data[0].s_Address);
                  
                
               

                
                $('#updateModal_sponsor').modal('show');
                
			},
			error:function(error){
			    $('#spinner_c').fadeOut('fast');
			    pop_wrong("Something went wrong!");
			}
        });
    });
    $("#update_sponsor_form").submit(function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            $('#spinne1rFu').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicatorFu').html("Saving");
            $.ajax({
                url: "../files/application/application_controller.php",
                type: "POST",
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                cache: false,
                success: function(formData){
                    $('#spinne1rsu').fadeOut('fast');
                    $('#indicatorsu').html("Save");
                    if(formData.status==200){
                        pop_up_success(formData.message)
                        window.location.reload();
                    }
                    if(formData.status==401){
                     pop_wrong(formData.message);   
                    }
                    if(formData.status==500){
                     pop_wrong(formData.message);   
                    }
                    
                },error: function(){
                    pop_wrong("Something went wrong!");
                    $('#spinne1rsu').fadeOut('fast');
                    $('#indicatorsu').html("Save");
                }
            });
        });
    

    })

   function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Info',
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