<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Timetable</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Timetable</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card mb-30">
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body row">
                                <input type="hidden" id="Ident" value="<?php echo $_SESSION['Identification']; ?>">
                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                    <label>Semester</label>
                                    <select class="form-control select2" style="width:100%" name="semester" id="semester">
                                        <?php
                                        $select_ac="SELECT * FROM tbl_acad_cycle WHERE status='1'";
                                        $cselect_ac=$conn->prepare($select_ac);
                                        $cselect_ac->execute();
                                        $row_cselect_ac=$cselect_ac->fetch(PDO::FETCH_ASSOC);
                                            $sql=$conn->prepare("SELECT 
                                            ts.*,
                                                s.* 
                                            FROM tbl_semester ts
                                            INNER JOIN semester s ON ts.semester = s.id where acad_year='".$row_cselect_ac['acad_cycle_id']."'");
                                            $sql->execute();
                                            while($sem=$sql->fetch()){
                                                ?>
                                        <option value="<?php echo $sem['semester']; ?>"><?php echo $sem['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                    <label style="opacity: 0;">Semester</label><br>
                                    <button type="button" id="load_btn" class="btn btn-icon btn-primary"><span id="spinner5"></span>&nbsp;<span id="indicator5">Load data</span>&nbsp;</button>
                                </div>
                            </div>
                            <div id="tables">

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('click', '#load_btn', function(){
            var getData= {
                    sem: $("#semester").val(),
                    staff_id: $("#Ident").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/lecturer.php",
                data: getData,
                success:function(data){
                    $('#spinner5').fadeOut('fast');
                    $('#tables').html(data);
				},
				error:function(error){
				    $('#spinner5').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        })
    });
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>