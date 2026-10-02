<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="campus" value="<?php echo $camp_id; ?>">
                        <section class="section">
                            <div class="section-header">
                                <h3>Semesters</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Semesters</a></div>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4 col-lg-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New Semester</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form id="savsemester" action="savsemester" method="POST">
                                                        <div class="card-body pb-0">
                                                            <div class="form-group">
                                                                <label>Academic Year</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <select class="form-control" name="acad_year" id="acad_year-1">
                                                                        <option selected disabled>Select Academic Year</option>
                                                                        <?php
                                                                        $select_academic="SELECT * FROM tbl_acad_cycle WHERE status='1'";
                                                                        $cselect_academic=$conn->prepare($select_academic);
                                                                        $cselect_academic->execute();
                                                                        foreach($cselect_academic as $row_cselect_academic){
                                                                        ?>
                                                                        <option value="<?php echo $row_cselect_academic['acad_cycle_id']?>"><?php echo $row_cselect_academic['acad_year']?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Semester</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <select class="form-control" name="semester" id="semester-1">
                                                                        <option selected disabled>Select Semester</option>
                                                                        <?php
                                                                        $select_semister="SELECT * FROM semester";
                                                                        $cselect_semister=$conn->prepare($select_semister);
                                                                        $cselect_semister->execute();
                                                                        foreach($cselect_semister as $row_cselect_semister){
                                                                        
                                                                        ?>
                                                                        <option value="<?php echo $row_cselect_semister['id']?>"><?php echo $row_cselect_semister['name']?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-primary form-control"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-8 col-lg-8">
                                        <div class="card" id="sample-login">
                                                <div class="card-header">
                                                    <h4>Registered semesters</h4>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="sem_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Academic Year</th>
                                                                <th scope="col">Semester</th>
                                                                <th scope="col">status</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_semester ORDER BY status ASC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($sem=$sql->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <?php
                                                                 $select_academic="SELECT * FROM tbl_acad_cycle WHERE acad_cycle_id='".$sem['acad_year']."'";
                                                                 $cselect_academic=$conn->prepare($select_academic);
                                                                 $cselect_academic->execute();
                                                                 $row_ccselect_academic=$cselect_academic->fetch(PDO::FETCH_ASSOC);
                                                                 
                                                                 $select_semester="SELECT * FROM semester WHERE id='".$sem['semester']."'";
                                                                 $cselect_semester=$conn->prepare($select_semester);
                                                                 $cselect_semester->execute();
                                                                 $row_ccselect_semester=$cselect_semester->fetch(PDO::FETCH_ASSOC);
                                                                 
                                                                ?>
                                                                <td><?php echo $row_ccselect_academic['acad_year'];?></td>
                                                                <td><?php echo $row_ccselect_semester['name']; ?></td>
                                                                <td><?php echo $sem['status']==1?"Active":"Inactive"; ?></td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <button type="button" data-id="<?php echo $sem['sem_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $sem['sem_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp; edit</button>
                                                                        <label class="custom-switch btn btn-light btn-sm">
                                                                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $sem['sem_id']; ?>" <?php echo $sem['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $sem['sem_id']; ?>"></span>&nbsp;
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Updating <span id="sem_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="sem_id" name="sem_id">
                    <!-- Academic Year Field -->
                    <div class="form-group">
                        <label>Academic Year</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                </div>
                            </div>
                            <input type="text" class="form-control" id="acad_year_display" readonly>
                            <input type="hidden" id="acad_cycle_id" name="acad_year">
                        </div>
                    </div>
                    <!-- Semester Field -->
                    <div class="form-group">
                        <label>Semester</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    &nbsp;<i class="fas fa-calendar"></i>&nbsp;
                                </div>
                            </div>
                            <select class="form-control" name="semester" id="semester">
                                <option selected disabled>Select Semester</option>
                                <?php
                                $select_semister="SELECT * FROM semester";
                                $cselect_semister=$conn->prepare($select_semister);
                                $cselect_semister->execute();
                                foreach($cselect_semister as $row_cselect_semister){
                                                                        
                                ?>
                                <option value="<?php echo $row_cselect_semister['id']?>"><?php echo $row_cselect_semister['name']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

                                   <!--end update modal-->
        </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $('#sem_table').DataTable(
         {     

      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       } 
        );
    //save academic cycle
    $("#savsemester").submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    var formData = {
        semester: $("#semester-1").val(),
        acad_year: $("#acad_year-1").val(),
        action: 'register'
    };

    // Show a spinner or loader
    $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $('#indicator').html("Saving...");

    $.ajax({
        url: "/files/Semester/controller.php",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(data) {
            // Hide spinner and update indicator text
            $('#spinner').fadeOut('fast');
            $('#indicator').html("Save");

            // Handle success response
            if (data.status == 200) {
                $('#savsemester')[0].reset(); // Reset the form
                pop_up_success(data.message); // Display success popup
                $('#sem_table').load(location.href + " #sem_table"); // Reload semester table
            }

            // Handle specific error responses
            if (data.status == 401 || data.status == 500) {
                pop_wrong(data.message); // Display error popup
            }
        },
        error: function(xhr, status, error) {
            // Handle AJAX errors
            $('#spinner').fadeOut('fast');
            $('#indicator').html("Save");
            var errorMessage = "Something went wrong!";
            if (xhr.responseText) {
                errorMessage += " Server says: " + xhr.responseText;
            }
            pop_wrong(errorMessage); // Display error popup
        }
    });
});

        
        //pre-update View
        $(document).on('click', '.edit', function () {
    var data_id = $(this).data('id');
    var getData = {
        id: data_id,
        action: 'view'
    };
    $('#spinner4_' + data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/Semester/controller.php",
        data: getData,
        dataType: "json",
        success: function (data) {
            $('#spinner4_' + data_id).fadeOut('fast');
            $("#sem_id").val(data_id);
            $("#acad_year_display").val(data.acad_year); // Display only
            $("#acad_cycle_id").val(data.acad_cycle_id); // Hidden field for submission
            $("#semester").val(data.semester); // Set the selected semester
            $("#sem_name").html(data.semester);
            $('#updateModal').modal('show');
        },
        error: function () {
            $('#spinner4_' + data_id).fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});

        
    //update program type
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = {
            sem_id:$("#sem_id").val(),
            semester:$("#semester").val(),
            acad_cycle_id:$("#acad_cycle_id").val(),
            action:'update'
                };
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Semester/controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#sem_table').load(location.href + " #sem_table");
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
        //delete semester
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'delete'
                    };
                  
            swal({
            title: "Are you sure?",
            text: "You are about to change this semester's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Semester/controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==500){
                        pop_wrong(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                       $('#sem_table').load(location.href + " #sem_table");
                    }
                    else if(data.status==401){
                       pop_up_success(data.message);
                    }
                    
				},
				error:function(error){
				    $('#spinner3_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong");
				}
            });
            }
           else {
                swal("operation cancelled!!");
            }
        });
        });
    });
</script>