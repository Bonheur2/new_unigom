<!--start of View Modal-->
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Salary Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tr>
                        <th>Family Name</th>
                        <th><span id="family_name"></th>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <th><span id="first_name"></span></th>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <th><span id="department"></span></th>
                    </tr>
                    <tr>
                        <th>Staff Type</th>
                        <th><span id="staff_type"></span></th>
                    </tr>
                    <tr>
                        <th>Academic Grade</th>
                        <th><span id="acc_grade"></span></th>
                    </tr>
                    <tr>
                        <th>Gross Salary</th>
                        <th><span id="gross_salary"></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!--End of View Modal-->
<!--start of edit Modal-->
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal2">
                <div class="modal-dialog" role="document">
                    <form action="" id="edit_gross_salary" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Staff Gross Salary</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                             <input type="hidden" id="st_id" name="st_id">
                            <span>Names : </span><span id="staff_name"></span>
                        </div>
                         <div class="form-group">
                             <label for="inputAddress">Gross Salary</label>
                            <input type="text" class="form-control" id="salary">
                          </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><span id="spinner83"></span>Save changes</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

<!--end of edit Modal-->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Staff Gross Salaries</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="gross_salary_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Salary</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $query1=$conn->prepare("SELECT i.family_name, i.first_name, i.email, i.phone, s.*,sp.status 
                                                                    FROM tbl_staff_info i 
                                                                        INNER JOIN tbl_staff_salary s ON i.staff_id=s.staff_Id 
                                                                        INNER JOIN tbl_staff_post sp ON i.staff_id=sp.staff_id WHERE sp.status=1");
                                    $query1->execute();
                                    $a=1;
                                    while($row=$query1->fetch()){
                                ?>
                                    <tr>
                                        <td><?php echo $a++ ?></td>
                                        <td><?php echo $row['family_name'];?></td>
                                        <td><?php echo $row['first_name'];?></td>
                                        <td><?php echo number_format($row['gross_salary'],2);?></td>
                                        <td>
                                            <button type="button" data-id="<?php echo $row['salary_Id'] ?>" class="btn btn-sm btn-primary view"><span class="spinner80"></span>view </button>
                                            <button type="button" data-id="<?php echo $row['salary_Id'] ?>" class="btn btn-sm btn-primary edit"><span class="spinner81"></span>Edit</button>

                                        </td>
                                    </tr>
                                <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
                        
     <!--scripts start     -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#gross_salary_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
    });
       
    $(document).on('click', '.view', function(e) {
       var spinner = $(this).find('.spinner80'); // Find the spinner within the clicked button
        spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        e.preventDefault();
        var value = $(this).data('id');
        var formData = {
            staff_id: value,
            action: "view_salary"
        };
        $.ajax({
          url: "/files/Payroll/controller.php",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(data){
           spinner.fadeOut('fast');
           $("#family_name").html(data.family_name);
           $("#first_name").html(data.first_name);
           $("#gross_salary").html(data.gross_salary);
           $("#department").html(data.staff_dept_full_name);
           $("#staff_type").html(data.staff_type_full_name);
           $("#acc_grade").html(data.acad_grad_full_name);
           $("#exampleModal").modal('show');
              
          },error: function () {
                spinner.fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    
    $(document).on('click', '.edit', function(e) {
       var spinner = $(this).find('.spinner81'); // Find the spinner within the clicked button
        spinner.html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        e.preventDefault();
        var value = $(this).data('id');
        var formData = {
            staff_id: value,
            action: "edit_salary"
        };
        $.ajax({
          url: "/files/Payroll/controller.php",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(data){
           var fullname=data.family_name+" "+data.first_name;
           spinner.fadeOut('fast');
           $("#staff_name").html(fullname);
           $("#salary").val(data.gross_salary);
           $("#st_id").val(data.salary_Id);
           $("#exampleModal2").modal('show');
              
          },error: function () {
                spinner.fadeOut('fast');
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    $("#edit_gross_salary").submit(function(e){
        e.preventDefault();
        $("#spinner83").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        var sal_id=$("#st_id").val();
        var salary=$("#salary").val();
        var formData={
            salary_id:sal_id,
            salary:salary,
            action:"confirm_edit"
        };
        $.ajax({
          url: "/files/Payroll/controller.php",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(data){
          if(data.status==200){
              pop_up_success(data.message);
               $("#exampleModal2").modal('hide');
               $("#spinner83").fadeOut('fast');
                $('#gross_salary_table').load(location.href + " #gross_salary_table");
          }
          if(data.status==500){
             pop_wrong(data.message);
             $("#spinner83").fadeOut('fast');
          }
              
          },error: function () {
                spinner.fadeOut('fast');
                pop_wrong("Something went wrong!");
                $("#spinner83").fadeOut('fast');
                $("#exampleModal2").modal('hide');
            }
        });
        
    })
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