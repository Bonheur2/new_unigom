<div class="main-content">
    <section class="section">
        <div class="section-header">
             <h3>Staff General Report</h3>
                <div class="section-header-breadcrumb">
                   <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Staff  Reports</a></div>
                            <div class="breadcrumb-item"><a href="#">General Report</a></div>
                </div>
        </div>
             <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12">
                            <div class="card">
                                    <div class="card-header" >
                                        <h4>Registered Staffs</h4>
                                        <div class="row col-lg-10">
                                            <div class="col-12 col-lg-9"></div>
                                            <div class="col-12 col-lg-3">
                                            <button class="btn btn-primary" type="submit" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excell</button>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="card-body pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm" id="staff_table">
                                                <thead>
                                                <tr>
                                                <th>#</th>
                                                <th>ID</th>
												<th>First Name</th>
												<th>Last Name</th> 
												<th>NID</th>
												<!--<th>Post</th>-->
												<!--<th>Department</th>-->
												<th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $query = $conn->prepare("SELECT si.*, sp.*
FROM tbl_staff_info si
INNER JOIN tbl_staff_post sp ON sp.staff_id = si.staff_id ");
                                                $query->execute();
                                                $i=1;
                                                while($rows=$query->fetch()){
                                                    $status=$rows['status'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i++ ?></td>
                                                         <td><?php echo $rows['staff_id'] ?></td>
                                                        <td><?php echo $rows['family_name'] ?></td>
                                                        <td><?php echo $rows['first_name'] ?></td>
                                                        <td><?php echo $rows['nid'] ?></td>
                                                        <!--<td><?php echo $rows['staff_post_full_name'] ?></td>-->
                                                        <!--<td><?php echo $rows['staff_dept_full_name'] ?></td>-->
                                                        <?php 
                                                        if($rows['status']==1){
                                                            ?>
                                                         <td><div class="badge badge-success">Active</div></td>   
                                                       <?php }
                                                       else{
                                                           ?>
                                                          <td><div class="badge badge-warning">Inactive</div></td> 
                                                      <?php }
                                                        ?>
                                                        
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
     $('#staff_table').DataTable({     
        "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 5
       }); 
   $("#exceldownload").click(function() {
       var action="general";
    window.open("../files/Staff_reports/allstaffexcelpdf.php?action=" +action, "_blank");
  });
});
</script>