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
                        <div class="card-header">
                            <h4>Generate</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body row">
                                <div class="form-group col-12 col-sm-3 col-lg-3">
                                    <label>Semester</label>
                                    <select class="form-control select2" style="width:100%" name="semester" id="semester">
                                        <?php
                                            $sql=$conn->prepare("SELECT * FROM  semester  ");
                                            $sql->execute();
                                            while($sem=$sql->fetch()){
                                                ?>
                                        <option value="<?php echo $sem['id']; ?>"><?php echo $sem['name']; ?> </option>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('click', '#load_btn', function(){
            var getData= {
                    sem: $("#semester").val()
            };
            $('#spinner5').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Timetable/list.php",
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