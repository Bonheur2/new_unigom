                    <!-- Start app main Content -->
                        <div class="main-content">
                            <section class="section">
                                <div class="section-header">
                                    <h3>Financial report</h3>
                                    <div class="section-header-breadcrumb">
                                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                        <div class="breadcrumb-item"><a href="#">Individual</a></div>
                                    </div><br>
                                </div>
                                <div class="section-body">
                                    <div class="card">
                                        <div class="card-body row">
                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                <label>Student ID</label><br>
                                                <select class="form-control select2" style="width:100%" name="reg_no" id="reg_no" required>
                                                    <option disabled selected>--choose one--</option>
                                                    <?php
                                                        $sql_stu=$conn->prepare("SELECT DISTINCT(r.reg_no), a.fname, a.lname, p.status
                                                                                        FROM tbl_register_program_ug r
                                                                                            INNER JOIN tbl_admission a ON r.reg_no=a.reg_no
                                                                                            INNER JOIN tbl_program_type p ON r.prg_type=p.prg_type_id
                                                                                        WHERE p.campus_id='".$camp_id."'");
                                                                                $sql_stu->execute();
                                                                                while($stu=$sql_stu->fetch()){
                                                                                    ?>
                                                                            <option value="<?php echo $stu['reg_no']; ?>"><?php echo $stu['reg_no']." | ".$stu['fname']." ".$stu['lname']; ?> </option>
                                                                            <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-12 col-sm-6 col-lg-6">
                                                <label>Report type</label><br>
                                                <select class="form-control select2" style="width:100%" name="type" id="type" required>
                                                    <option value="1">Balance</option>
                                                    <option value="2">Invoice history</option>
                                                    <option value="3">Payment history</option>
                                                    <option value="4">Cumulative payments</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-12" style="display:flex;flex-direction:row;justify-content:center;">
                                                <span id="loader"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="report">

                                    </div>
                                </div>
                            </section>
                        </div>
                    
                
    <!--javascript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script>
    $(document).ready(function(){
        
        $('#report_table').DataTable({     
          "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "iDisplayLength": 5
       });

        //load student and dept info  
        $(document).on('change','#reg_no, #type',function () {
            var formdata = {
                stu: $("#reg_no").val(),
                type: $("#type").val()
            };
            $('#loader').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Financial_report/load_ind_report.php",
                data: formdata,
                mimeTypes:"multipart/form-data",
                success: function (data) {
                    $('#loader').fadeOut('fast');
                    $("#report").html(data);
                    $("#report_table").DataTable().draw();
                },
                error:function(error){
                    $('#loader').fadeOut('fast');
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