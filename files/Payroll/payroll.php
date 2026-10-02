<!-- Start app main Content -->
        <div class="main-content">
            <input type="hidden" id="camp_id" value="<?php echo $camp_id; ?>">
                    <section class="section">
                        <div class="section-header">
                            <h3>Payroll</h3>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Payroll</a></div>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-body">
                                        <div class="col-12 col-sm-10 col-lg-9 m-auto">
                                            <form id="load_payroll" action="" method="POST">
                                                <input type="hidden" name="camp_id" value="<?php echo $camp_id; ?>">
                                                <div class="col-12 row">
                                                    <div class="form-group col-3 col-sm-2 col-lg-2 d-flex align-items-center">
                                                        <label class="floating-label">Month</label>
                                                    </div>
                                                    <div class="form-group col-9 col-sm-9 col-lg-7 d-flex align-items-center justify-content-center">
                                                        <input type="month" name="month" id="month" class="form-control" value="<?php echo date('Y-m'); ?>">
                                                    </div>
                                                    <div class="form-group col-12 col-sm-3 col-lg-3 d-flex align-items-center justify-content-center">
                                                        <button type="submit" class="btn btn-primary" id="btn"><span id="spinner"></span>&nbsp;<span id="indicator">Load data</span></button>
                                                    </div> 
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12" id="results" hidden>
                                    <div class="card">
                                        <div class="card-header">
                                            <h4><span id="Month"></span>&nbsp;Payroll</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="payroll">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Staff</th>
                                                            <th scope="col">Gross salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contents"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="text-align:right;">
                                            <button type="button" class="btn btn-icon btn-light btn-sm" onclick="OpenPopupCenter('Payroll', 800, 600);"><i class="fas fa-download"></i>&nbsp;Export PDF</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $("#load_payroll").submit(function(e){
            e.preventDefault();
            var dateString = $("#month").val();
            var dateParts = dateString.split("-");
            var year = parseInt(dateParts[0]);
            var month = parseInt(dateParts[1]);
            var formattedDate = new Date(year, month - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Loading...");
            $.ajax({
                url: "/files/Payroll/load_payroll.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                mimeTypes: "multipart/form-data",
                success: function(data){
                    $("#results").removeAttr('hidden');
                    $('#payroll').DataTable().destroy();
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Load data");
                    $('#Month').html(formattedDate);
                    $('#contents').html(data);
                    $('#payroll').DataTable({"aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]], "iDisplayLength": 10});
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Load data");
                    pop_wrong("Something went wrong!");
                }
            });
        });
    });
    
        
function OpenPopupCenter(title, w, h) {
    var pageURL = '/files/Payroll/printPayroll?m='+$("#month").val(); 
    var left = (screen.width - w) / 2;
    var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
} 
</script>