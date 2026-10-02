<div class="main-content">
    <section class="section">
  <div class="row">
            <div class="col-12 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Staff Working Hours</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="gross_salary_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Department</th>
                                        <th>Hours</th>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
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
             <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Register Staff Working Hours</h4>
                                </div>
                                <form action="" id="staff_hours_form" method="POST">
                                <div class="card-body">
                                    <div>Department</div>
                                    <div class="form-group">
                                         <select name="department" id="department"  class="form-control select2" style="width:100%">
                                         <option value='' disabled selected>--Choose one--</option>
                                               <?php
                                               $sql_dep=$conn->prepare("SELECT * FROM tbl_department WHERE status=1");
                                               $sql_dep->execute();
                                                $i=1;
                                              while($dep=$sql_dep->fetch()){
                                             ?>
                                         <option value="<?php echo $dep['dept_id']; ?>"><?php echo $dep['dept_full_name']; ?> </option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                    <div class="form-group" id="staff_info" style="display:block">
                                          <label>Staff</label>
                                          <select name="staff_name" id="staff_name" class="form-control select2" style="width:100%">
                                            <option value="" disabled selected>--Choose one--</option>
                                          </select>
                                        </div>
                                    <div class="form-group">
                                            <label for="inputEmail4">Hours</label>
                                            <input type="number" class="form-control" id="hours" placeholder="Enter Working Hours">
                                    </div>
                                   <button type="submit" class="btn btn-primary float-right">Save</button>
                                   </div>
                                </form>
                            </div>
                        </div>
                    </div> 
             </section>     
            </div>
            
            
<!--scripts start-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
$('#department').change(function () {
var getData = {
            dep: $('#department').val(),
            action: 'loadstaff'
        };
      $.ajax({
        url: "/files/Staff/workhoursController.php",
        method: 'POST',
        data:getData,
        success: function (response) {
          $('#staff_name').html(response);
        }
      });
    }
  });
});
</script>
<!--scripts end-->