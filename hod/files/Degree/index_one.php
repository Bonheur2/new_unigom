<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $title ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=1">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">List</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body pb-0 row">
                                <div class="form-group  col-12 col-sm-4 col-lg-4">
                                    <label>Student ID</label><br>
                                    <input type="text" class="form-control" style="width:100%" id="student" required>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label style="opacity: 0;">Student ID</label><br>
                                    <button type="button" class="btn btn-primary" id="print"><i class="fas fa-wifi"></i>&nbsp;Go</button>
                                </div> 
                            </div>
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
        $("#print").click(function(e){
            var student = $('#student').val();
        	const pageURL = "/files/Degree/export_one?stu="+student;

            var left = (screen.width - 800) / 2;
            var top = (screen.height - 600) / 4;
            window.open(pageURL, "Degrees", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=600, top=' + top + ', left=' + left);
        });
    });
</script>