                <!-- Start app main Content -->
                    <div class="main-content">
                        <section class="section">
                            <div class="section-header">
                                <h3>Student sponsors</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">sponsors</a></div>
                                </div><br>
                            </div>
                            <div class="section-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>New sponsorship</h4>
                                                    <div class="card-header-action">
                                                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                    </div>
                                                </div>
                                                <div class="card-body collapse hide"id="mycard-collapse">
                                                    <form id="assign_sponsor" action="assign_sponsor" method="POST">
                                                        <input type="hidden" name="action" value="assign_sponsor">
                                                        <div class="row">
                                                            <div class="col-12 col-sm-6 col-lg-6">
                                                                <div class="form-group  col-12">
                                                                    <label>Student ID</label><br>
                                                                    <select class="form-control select2" style="width:100%" name="stu" id="stu" required>
                                                                        <?php
                                                                            $sql_stu=$conn->prepare("SELECT DISTINCT(r.reg_no),
                                                                                                            a.fname,a.lname,
                                                                                                            p.status
                                                                                                            FROM tbl_register_program_ug r
                                                                                                                INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                                                                INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                                                            WHERE p.campus_id='".$camp_id."'");
                                                                            $sql_stu->execute();
                                                                            while($stu=$sql_stu->fetch()){ ?>
                                                                        <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group  col-12">
                                                                    <label>Sponsor</label><br>
                                                                    <select class="form-control select2" style="width:100%" name="sponsor" required>
                                                                        <?php
                                                                            $sql=$conn->prepare("SELECT * FROM tbl_sponsor WHERE status=1 ORDER BY tbl_sponsor.spon_full_name ASC");
                                                                            $sql->execute();
                                                                            while($spon=$sql->fetch()){
                                                                         ?>
                                                                                ?>
                                                                        <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    </div>
                                                                    <div class="form-group  col-12">
                                                                    <label>Sponsorship Percentage</label><br>
                                                                    <select class="form-control select2" style="width:100%" name="sponsorship_percentage" required>
                                                                        <?php
                                                                            for ($i = 1; $i <= 100; $i++) {
                                                                         ?>
                                                                                ?>
                                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> %</option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-lg-6">
                                                                <div class="card-header">
                                                                    <h4>Payable Fees</h4>
                                                                </div>
                                                                <div class="card-body">
                                                                   <table class="table table-hover table-sm" id="fee_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Fee Name</th>
                                                                            <th>Select</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="fee_table_body">
                                                                        <!-- Fee categories will be populated here -->
                                                                    </tbody>
                                                                </table> 
                                                                    <div class="form-group  col-12 col-sm-12 col-lg-12" id="btn">
                                                                        <center>
                                                                            <button id="rst" type="button" class="btn btn-light btn-sm">Reset</button>&nbsp;
                                                                            <button id="sp" type="submit" class="btn btn-primary btn-sm"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                                        </center>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Registered sponsors</h4>
                                                    <div class="card-header-action">
                                                        <a data-collapse="#mycard-collapse-2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                    </div>
                                                </div>
                                                <div class="card-body collapse show" id="mycard-collapse-2">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm" id="sponsor_table">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Student ID</th>
                                                                <th scope="col">Names</th>
                                                                <th scope="col">Fee Offered</th>
                                                                <th scope="col">Sponsor</th></th>
                                                                <th scope="col">Percentage (%)</th></th>
                                                                <th scope="col">Action</th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_stu_sponsor ss
                                                                                           INNER JOIN tbl_sponsor s ON ss.sponsor_id=s.spon_id
                                                                                            INNER JOIN tbl_fee_category f ON ss.fee_id=f.id
                                                                                        ORDER BY ss.status ASC");
                                                                $sql->execute();
                                                                $i=1;
                                                                while($sp=$sql->fetch()){
                                                                $s_regno=$sp['reg_no'];
                                                                $sql2=$conn->prepare("SELECT * FROM tbl_admission WHERE reg_no='".$s_regno."' LIMIT 1");
                                                                $sql2->execute(); 
                                                               while($std=$sql2->fetch()){
                                                             ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                <td><?php echo $sp['reg_no']; ?></td>
                                                                <td><?php echo $std['lname']; "  "?> <?php echo $std['fname']; ?></td>
                                                                <td><?php echo  $sp['name']; ?></td>
                                                                <td><?php echo $sp['spon_full_name']; ?></td>
                                                                <td><?php echo $sp['sponsor_percentage']; ?> %</td>
                                                                <th>  
                                                                    <div class="buttons row">
                                                                        <?php if($sp['status']==1){ ?>
                                                                        <button type="button" data-id="<?php echo $sp['stu_sponsor_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $sp['stu_sponsor_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i></button>
                                                                        <?php } ?>
                                                                        <label class="custom-switch btn btn-light btn-sm">
                                                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $sp['stu_sponsor_id']; ?>" <?php echo $sp['status']==1?'checked':''; ?>>
                                                                            <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $sp['stu_sponsor_id']; ?>"></span>&nbsp;
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <?php }} ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                           
                                <!--update modal-->
                                <form action="update_form" method="POST" id="update_form">
                                    <div class="modal fade" role="dialog" id="updateModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Updating <span id="sname"></span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="stu_sponsor_id" name="stu_sponsor_id">
                                                    <input type="hidden" value="update_sponship" name="action">
                                                    <div class="form-group">
                                                        <label>Sponsor</label><br>
                                                        <select class="form-control select2" style="width:100%" name="sponsor" id="e_sponsor_id" required>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_sponsor WHERE status=1 ORDER BY tbl_sponsor.spon_full_name ASC");
                                                                $sql->execute();
                                                                while($spon=$sql->fetch()){
                                                            ?>
                                                            <option value="<?php echo $spon['spon_id']; ?>"><?php echo $spon['spon_full_name']; ?> </option>
                                                            <?php } ?>
                                                        </select>
                                                        
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Percentage</label><br>
                                                                <?php
                                                                $sql=$conn->prepare("SELECT * FROM tbl_stu_sponsor");
                                                                $sql->execute();
                                                                $spon=$sql->fetch()
                                                                 ?>
                                                        <input type="number" max="100" min="0" class="form-control" value="<?php echo $spon['sponsor_percentage']; ?>"  name="sponsor_percentage" id="e_sponsor_id" style="width:100%" required>
                                                        </div>
                                                         
             
                                                </div>
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"><span id="spinner2"></span>&nbsp;<span id="indicator2">Save changes</span></button>
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
    function viewMore(button) {
    var row = button.closest('tr');
    while (row = row.nextElementSibling) {
        if (row.classList.contains('duplicate-row')) {
            row.style.display = '';
        } else {
            break;
        }
    }
    button.style.display = 'none';
}
    $(document).ready(function(){
        
        $('#sponsor_table').DataTable({     
          "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
       });

        //UI master
        $(document).on('click', '.fee',function () {
            var checkedBoxes = $('input[type=checkbox]:checked');
            var numCheckedBoxes = checkedBoxes.length;
            if(numCheckedBoxes==0){
                $("#btn").attr('hidden',true);
            }
            else{
                $("#btn").attr('hidden',false);
            }
        });
        
        $(document).on('click', '#rst',function () {
            $('input[type=checkbox]').prop('checked', false);
            $("#btn").attr('hidden',true);
        });
        
         $("#stu").change(function () {
    console.log('clicked');
    var student = $("#stu").val();

    var formdata = {
        reg_no: student,
        action: "load_student_fee"
    };
    $('#fee_table').css({'display': 'none'});

    $.ajax({
        type: "POST",
        url: "/files/Sponsors/sponsor_controller.php",
        data: formdata,
        dataType: "JSON",
        success: function (data) {
            $('#spinner0').fadeOut('fast');
            $('#fee_table').css({'display': 'block'}); // Show program section

            // Clear the table body
            $('#fee_table_body').empty();

            // Populate the table with fee categories
            data.forEach(function (fee) {
                var row = '<tr>' +
                    '<td>' + fee.name + '</td>' +
                    '<td><input type="checkbox" name="fee[]" class="fee" value="' + fee.id + '" checked></td>' +
                    '</tr>';
                $('#fee_table_body').append(row);
            });
        },
        error: function (error) {
            $('#spinner0').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});
        
        
        
        
        //assign sponsor
        $("#assign_sponsor").submit(function(e){
                e.preventDefault();
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $('#sp').attr('disabled',true);
            $.ajax({
                url: "/files/Sponsors/sponsor_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    $('#sp').attr('disabled',false);
                    if(data.status==200){
                        pop_up_success(data.message);
                        $('#sponsor_table').load(location.href + " #sponsor_table");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    $('#sp').attr('disabled',false);
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_sponship'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Sponsors/sponsor_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#stu_sponsor_id").val(data_id);
                    var selectElement = document.getElementById('e_sponsor_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.sponsor_id + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                      selectElement.prepend(selectedOption);
                    }
                    $("#sname").html(data.reg_no+" | "+data.fee_name);
                    $("#sponsor_percentage").html(data.sponsor_percentage);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View
        $(document).on('click','.edit',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'view_sponship'
                    };
            $('#spinner4_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Sponsors/sponsor_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner4_'+data_id).fadeOut('fast');
                    $("#stu_sponsor_id").val(data_id);
                    var selectElement = document.getElementById('e_sponsor_id');
                    
                    // Set selected value
                    var selectedOption = selectElement.querySelector('option[value="' + data.sponsor_id + '"]');
                    if (selectedOption) {
                      selectedOption.selected = true;
                      selectElement.prepend(selectedOption);
                    }
                    $("#sname").html(data.reg_no+" | "+data.fee_name);
                    $('#updateModal').modal('show');
				},
				error:function(error){
				    $('#spinner4_'+data_id).fadeOut('fast');
                    pop_wrong("Something went wrong!");
				}
            });
        });
        
        
    //update program type
    $("#update_form").submit(function(e){
            e.preventDefault();
    
        var formData = new FormData(this);
        $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2').html("Saving...");
            $.ajax({
                url: "/files/Sponsors/sponsor_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    if(data.status==200){
                        $('#update_form')[0].reset();
                        pop_up_success(data.message);
                        $('#updateModal').modal('hide');
                        $('#sponsor_table').load(location.href + " #sponsor_table");
                    }
                    if(data.status==401){
                        pop_info(data.message);
                    }
                },error: function(){
                    $('#spinner2').fadeOut('fast');
                    $('#indicator2').html("Save Changes");
                    pop_wrong("Something went wrong!");
                }
             });
          });
          
        // delete sponsor
        $(document).on('click','.del',function () {
            var data_id = $(this).data('id');
            var getData= {
                    id: data_id,
                    action:'on-off-sponship'
                    };
            swal({
            title: "Are you sure?",
            text: "You are about to change this sponsor's status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
            $('#spinner3_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Sponsors/sponsor_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $('#spinner3_'+data_id).fadeOut('fast');
                    if(data.status==401){
                        pop_info(data.message); 
                    }
                    else if(data.status==200){
                       pop_up_success(data.message); 
                       $('#sponsor_table').load(location.href + " #sponsor_table");
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