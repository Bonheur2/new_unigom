<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>My information</h3>
            <div class="section-header-breadcrumb">
                <a href="../files/Financial_report/download_student_info.php?stu=<?php echo $identification; ?>" class="btn btn-success btn-sm mr-3" target="_blank">
                    <i class="fas fa-download"></i> Download PDF
                </a>
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">My information</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row" id="profile">
                <?php
                $stmt=$conn->prepare("SELECT tbl_admission.*,
                                             tbl_nationality.nationality as nat,
                                             tbl_country.cntr_name as cname,
                                             provinces.provincename as pname,
                                             districts.namedistrict as dname,
                                             sectors.namesector as sname,
                                             cells.namecell as cellname,
                                             villages.VillageName as vname
                                             FROM tbl_admission
                                             LEFT JOIN tbl_nationality ON
                                             tbl_admission.nationality=tbl_nationality.nat_id
                                             LEFT JOIN tbl_country ON
                                             tbl_admission.country=tbl_country.cntr_id
                                             LEFT JOIN provinces ON
                                             tbl_admission.province_id=provinces.provincecode
                                             LEFT JOIN districts ON
                                             tbl_admission.district_id=districts.districtcode
                                             LEFT JOIN sectors ON
                                             tbl_admission.sector=sectors.sectorcode
                                             LEFT JOIN cells ON
                                             tbl_admission.cell_id=cells.codecell
                                             LEFT JOIN villages ON
                                             tbl_admission.village_id=villages.CodeVillage
                                             WHERE tbl_admission.reg_no='".$identification."' OR tbl_admission.reg_no='".$_GET['stu']."'");
                $stmt->execute();
                $stuData=$stmt->fetch();
                ?>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Firstname:</th>
                                            <th scope="col"><?php echo $stuData['fname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Lastname:</th>
                                            <th scope="col"><?php echo $stuData['lname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Gender:</th>
                                            <th scope="col"><?php echo $stuData['gender']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nationality:</th>
                                            <th scope="col"><?php echo $stuData['nationality']!=0?$stuData['nat']:'Others'; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">ID/passport:</th>
                                            <th scope="col"><?php echo $stuData['ID']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Father:</th>
                                            <th scope="col"><?php echo $stuData['father_names']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Mother:</th>
                                            <th scope="col"><?php echo $stuData['mother_names']; ?></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                    <button class="btn btn-primary btn-sm" id="update_p" data-id="<?php echo $identification; ?>"><span id="spinner_p"></span>&nbsp;<span id="indicator_p">update info</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Address information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Country:</th>
                                            <th scope="col"><?php echo $stuData['cname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Province:</th>
                                            <th scope="col"><?php echo $stuData['province_id']!=0?$stuData['pname']:'N/A'; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">District:</th>
                                            <th scope="col"><?php echo $stuData['district_id']!=0?$stuData['dname']:'N/A'; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Street:</th>
                                            <th scope="col"><?php echo $stuData['street']!=''?$stuData['street']:'N/A'; ?></th>
                                        </tr>
                                        
                                    </thead>
                                    </table>
                                </div>
                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                    <button class="btn btn-primary btn-sm" id="update_a" data-id="<?php echo $identification; ?>"><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Contact information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse3" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse3">
                            <div class="card-body">
                                <div class=" ">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Email:</th>
                                            <th scope="col"><?php echo $stuData['email']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Phone:</th>
                                            <th scope="col"><?php echo $stuData['phone']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Parent's Phone:</th>
                                            <th scope="col"><?php echo $stuData['parent_phone']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Second Phone:</th>
                                            <th scope="col"><?php echo $stuData['ref_phone']; ?></th>
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                    <button class="btn btn-primary btn-sm" id="update_c"  data-id="<?php echo $identification; ?>"><span id="spinner_c"></span>&nbsp;<span id="indicator_c">update info</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--update modal personal-->
<form action="update_form_p" method="POST" id="update_form_p">
    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_p">
        <div class="modal-dialog" role="document">
            <input type="hidden" name="action" value="update_personal">
            <input type="hidden" name="stu" id="stu">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Updating Personal Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Surname (Family name)</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="lname" id="lname" placeholder="e.g. GASANA" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>First name</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="fname" id="fname" placeholder="e.g. Annet" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nationality</label>
                        <div class="input-group">
                            <select name="nationality" id="nationality"  class="form-control select2" style="width:100%;">
                                <?php
                                    $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                    $sql_nat->execute();
                                    $i=1;
                                    while($nat=$sql_nat->fetch()){
                                ?>
                                    <option value="<?php echo $nat['nat_id']; ?>"><?php echo $nat['nationality']; ?> </option>
                                    <?php } ?>
                                    <option value="0">Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>ID/ Passport</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-id-card"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="nid" id="enid" placeholder="e.g. 1 1990 800 xxxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <div class="input-group">
                            <select name="gender" id="gender"  class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                <option value="" disabled selected hidden>Choose One...</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Father's name</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <input type="text" name="father_names" id="father_names" class="form-control" placeholder="father's full names" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mother's name</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <input type="text" name="mother_names" id="mother_names" class="form-control" placeholder="mother's full names" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end update modal personal-->  

<!--update modal contact-->
<form action="update_form_c" method="POST" id="update_form_c">
    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_c">
        <div class="modal-dialog" role="document">
            <input type="hidden" name="action" value="update_contact">
            <input type="hidden" name="stuc" id="stuc">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Updating Contact Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <input type="email" class="form-control" name="email" id="email" placeholder="e.g. gasana@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-mobile"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="e.g. +250788888888" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Parent Phone</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="parent_phone" id="parent_phone" placeholder="e.g. +250788888888">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Second Phone (optional)</label>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <input type="text" name="ref_phone" id="ref_phone" class="form-control" placeholder="e.g. +250788888888">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><span id="spinner2_c"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end update modal contact-->

<!--update modal address-->
<form action="update_form_a" method="POST" id="update_form_a">
    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_a">
        <div class="modal-dialog" role="document">
            <input type="hidden" name="action" value="update_address">
            <input type="hidden" name="stua" id="stua">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Updating Address Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Country of residence</label>
                        <select name="country" id="country" class="form-control select2" style="width:100%;">
                            <?php
                                $sql_cntr=$conn->prepare("SELECT * FROM tbl_country");
                                $sql_cntr->execute();
                                $i=1;
                                while($cntr=$sql_cntr->fetch()){
                            ?>
                                <option value="<?php echo $cntr['cntr_id']; ?>"><?php echo $cntr['cntr_name']." [ ".$cntr['cntr_code']." ]"; ?> </option>
                                <?php } ?>
                        </select><span id="spinner_cntr"></span> 
                    </div>
                    <div class="form-group" id="prov">
                        <label>Province</label>
                        <select name="province_id" id="province_id" class="form-control select2" style="width:100%;">
                        </select><span id="spinner_prov"></span>
                    </div>
                    <div class="form-group" id="district">
                        <label>District</label>
                        <select name="district_id" id="district_id" class="form-control select2" style="width:100%;">
                            
                        </select><span id="spinner_dis"></span>
                    </div>
                    <div class="form-group" id="sect">
                        <label>Street</label>
                        <input type="text" name="street" id="street" class="form-control" style="width:100%;">
                      </div>
                  
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uBtn"><span id="spinner2_a"></span>&nbsp;<span id="indicator2_a">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end update modal personal-->

<!--update modal academic-->
<form action="update_form_acc" method="POST" id="update_form_acc">
    <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_acc">
        <div class="modal-dialog modal-lg" role="document">
            <input type="hidden" name="action" value="update_acc">
            <input type="hidden" name="reg_prg_id" id="reg_prg_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Updating Academic Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-6" id="prg">
                            <label>Student ID</label>
                            <input type="text" class="form-control" name="reg_no" id="stu_reg_no" readonly required>
                        </div>
                        <div class="form-group col-12 col-md-6" id="prg">
                            <label>Program Type</label>
                            <select class="form-control select2" style="width:100%" name="prg_type" id="prg_type" required>
                                <?php
                                    $sql_prg=$conn->prepare("SELECT 
                                                    tbl_program_type.*,
                                                    tbl_campus.camp_full_name 
                                                        FROM tbl_program_type 
                                                    INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id 
                                                        ORDER BY tbl_program_type.status ASC");
                                    $sql_prg->execute();
                                    $i=1;
                                    while($progs_faculty=$sql_prg->fetch()){
                                        ?>
                                <option value="<?php echo $progs_faculty['prg_type_id']; ?>"><?php echo $progs_faculty['prg_type_full_name']." | ".$progs_faculty['camp_full_name']; ?> </option>
                                <?php } ?>
                            </select>
                            <span id="spinner00"></span>
                        </div>
                        <div class="form-group col-12 col-md-6" id="fct">
                            <label>Faculty</label>
                            <select  class="form-control select2" style="width:100%" name="fac_id" id="fac_id" required>
                            </select>
                            <span id="spinner000"></span>
                        </div>
                        <div class="form-group col-12 col-md-6" id="dept">
                            <label>Department</label>
                            <select  class="form-control select2" style="width:100%" name="dept_id" id="dept_id" required>
                            </select>
                            <span id="spinner0000"></span>
                        </div>
                        <div class="form-group col-12 col-md-6" id="spec">
                            <label>Specialization</label>
                            <select  class="form-control select2" style="width:100%" name="splz_id" id="splz_id" required>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6" id="level">
                            <label>Level</label>
                            <select  class="form-control select2" style="width:100%" name="level_id" id="level_id" required>
                            </select>

                        </div>
                        <div class="form-group col-12 col-md-6" id="intake">
                            <label>Intake</label>
                            <select  class="form-control select2" style="width:100%" name="intake_id" id="intake_id" required>
                            </select>

                        </div>
                        <div class="form-group col-12 col-md-6" id="mod">
                            <label>Learning Mode</label>
                            <select  class="form-control select2" style="width:100%" name="prg_mode_id" id="prg_mode_id">
                                <?php
                                    $sql_mode=$conn->prepare("SELECT * FROM tbl_program_mode");
                                    $sql_mode->execute();
                                    $i=1;
                                    while($progs_mode=$sql_mode->fetch()){
                                        ?>
                                <option value="<?php echo $progs_mode['prg_mode_id']; ?>"><?php echo $progs_mode['prg_mode_full_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uacBtn"><span id="spinner5_acc"></span>&nbsp;<span id="indicator5_acc">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end update modal academic-->

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    $(document).ready(function(){
        //pre-update View personal
        $('#update_p').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_p').fadeOut('fast');
                    $("#stu").val(data_id);
                    $("#fname").val(data[0].fname);
                    $("#lname").val(data[0].lname);
                    $("#enid").val(data[0].ID);
                    $("#mother_names").val(data[0].mother_names);
                    $("#father_names").val(data[0].father_names);
                    var selectElement = document.getElementById('gender');
                    var selectElement2 = document.getElementById('nationality');
                    var selectedOption = selectElement.querySelector('option[value="' + data[0].gender + '"]');
                    var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].nationality + '"]');
                    if(selectedOption){
                        selectedOption.selected = true;
                        selectElement.prepend(selectedOption);
                    }
                    if(selectedOption2){
                        selectedOption2.selected = true;
                        selectElement2.prepend(selectedOption2);
                    }
                    $('#updateModal_p').modal('show');
				},
				error:function(error){
				    $('#spinner_p').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View contact
        $('#update_c').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_c').fadeOut('fast');
                    $("#stuc").val(data_id);
                    $("#phone").val(data[0].phone);
                    $("#email").val(data[0].email);
                    $("#parent_phone").val(data[0].parent_phone);
                    $("#ref_phone").val(data[0].ref_phone);
                    $('#updateModal_c').modal('show');
				},
				error:function(error){
				    $('#spinner_c').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View address
        $('#update_a').click(function () {
            var data_id = $(this).data('id');
            var getData= {
                    stu: data_id,
                    action:'load_info'
                    };
            $('#spinner_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_a').fadeOut('fast');
                    $("#stua").val(data_id);
                    var selectElement0 = document.getElementById('country');
                    if(data[0].country==160){
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                        var selectElement1 = document.getElementById('province_id');
                        var selectElement2 = document.getElementById('district_id');
                        var selectElement3 = document.getElementById('sector');
                        var selectElement4 = document.getElementById('cell_id');
                        var selectElement5 = document.getElementById('village_id');
                        $.each(data[2], function (index, value) {
                                $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename +"</option>");
                            });
                        $.each(data[3], function (index, value) {
                                $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict +"</option>");
                            });
                        $.each(data[4], function (index, value) {
                                $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector +"</option>");
                            });
                        $.each(data[5], function (index, value) {
                                $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell +"</option>");
                            });
                        $.each(data[6], function (index, value) {
                                $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName +"</option>");
                            });
                        
                        // Set selected values
                        var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].province_id + '"]');
                        var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].district_id + '"]');
                        var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].sector + '"]');
                        var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].cell_id + '"]');
                        var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].village_id + '"]');
                        if(selectedOption1){
                            selectedOption1.selected = true;
                            selectElement1.prepend(selectedOption1);
                        }
                        if(selectedOption2){
                            selectedOption2.selected = true;
                            selectElement2.prepend(selectedOption2);
                        }
                        if(selectedOption3){
                            selectedOption3.selected = true;
                            selectElement3.prepend(selectedOption3);
                        }
                        if(selectedOption4){
                            selectedOption4.selected = true;
                            selectElement4.prepend(selectedOption4);
                        }
                        if(selectedOption5){
                            selectedOption5.selected = true;
                            selectElement5.prepend(selectedOption5);
                        }
                    }
                    else{
                        $("#prov").attr('hidden',true);
                        $("#district").attr('hidden',true);
                        $("#sect").attr('hidden',true);
                        $("#cell").attr('hidden',true);
                        $("#village").attr('hidden',true);
                    }
                        var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                        if(selectedOption0){
                            selectedOption0.selected = true;
                            selectElement0.prepend(selectedOption0);
                        }
                        
                    $('#updateModal_a').modal('show');
				},
				error:function(error){
				    $('#spinner_a').fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });
        
        //pre-update View passes
        $(document).on('click','.editp', function () {
            var data_id = $(this).data('id');
            var getData= {
                    course_id: data_id,
                    action:'load_pass_info'
                    };
            $('#spinner_f_'+data_id).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/Student/student_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_f_'+data_id).fadeOut('fast');
                    $("#course_id").val(data_id);
                    $("#course").val(data.course);
                    $("#grade").val(data.grade);
                    $('#updateModal_pass').modal('show');
				},
				error:function(error){
				    $('#spinner_f_'+data_id).fadeOut('fast');
				    pop_wrong("Something went wrong!");
				}
            });
        });

        //Update Personal
        $("#update_form_p").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_p').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_p").modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_p').fadeOut('fast');
                    $('#indicator2_p').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //Update contact
        $("#update_form_c").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_c').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_c").modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
              
        //Update address
        $("#update_form_a").submit(function(e){
                e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner2_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator2_a').html("Saving...");
            $.ajax({
                url: "/files/Student/student_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Save changes");
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_a").modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                    if(data.status==500){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Save changes");
                    pop_wrong("Something went wrong!");
                }
            });
        });
        
        //update passes
        $("#update_form_pass").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner10_pass').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator10_pass').html("Saving...");
            $("#upassBtn").attr('disabled', true);
            $.ajax({
                url: "/files/admission/admission_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Save changes");
                    $("#upassBtn").removeAttr('disabled');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#updateModal_pass").modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }
                    if(data.status==401){
                        pop_wrong(data.message);
                    }
                },error: function(){
                    $("#upassBtn").removeAttr('disabled');
                    $('#spinner10_pass').fadeOut('fast');
                    $('#indicator10_pass').html("Save");
                    pop_wrong("Something went wrong!");
                }
            });
        });
          
        //load provinces
        $('#country').change(function () {
                $("#province_id").attr('required', true);
                $("#district_id").attr('required', true);
                $("#sector").attr('required', true);
                $("#cell_id").attr('required', true);
                $("#village_id").attr('required', true);
                $("#uBtn").attr('hidden', true);
                $("#prov").attr('hidden', true);
                $("#district").attr('hidden', true);
                $("#sect").attr('hidden', true);
                $("#cell").attr('hidden', true);
                $("#village").attr('hidden', true);
                $("#province_id").empty();
                $("#district_id").empty();
                $("#sector").empty();
                $("#cell_id").empty();
                $("#village_id").empty();
                
                var getData= {
                        action:'load_provinces'
                        };
                $('#spinner_cntr').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "/files/application/application_controller.php",
                    data: getData,
                    dataType:"JSON",
                    success:function(data){
                        $('#spinner_cntr').fadeOut('fast');
                        $("#province_id").append("<option></option>")
                        $.each(data, function (index, value) {
                            $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename+"</option>");
                        });
                        $("#prov").attr('hidden',false);
    				},
    				error:function(error){
    				    $('#spinner_cntr').fadeOut('fast');
                        pop_wrong("Something went wrong!"); 
    				}
                });
   
        });

        //load districts
        $('#province_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#district").attr('hidden', true);
            $("#sect").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    pid:$('#province_id').val(),
                    action:'load_districts'
                    };
            $("#district_id").empty();
            $('#spinner_prov').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_prov').fadeOut('fast');
                    $("#district_id").append("<option></option>")
                    $.each(data, function (index, value) {
                        $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict+"</option>");
                    });
                    $("#district").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_prov').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load sectors
        $('#district_id').change(function () {
            $("#uBtn").attr('hidden', false);
             $("#sect").attr('hidden', false);
        });
        
        //load cells
        $('#sector').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#cell").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    sid: $('#sector').val(),
                    action:'load_cells'
                    };
            $("#cell_id").empty();
            $('#spinner_sect').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_sect').fadeOut('fast');
                    $("#cell_id").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell+"</option>");
                    });
                    $("#cell").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_sect').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load villages
        $('#cell_id').change(function () {
            $("#uBtn").attr('hidden', true);
            $("#village").attr('hidden', true);
            var getData= {
                    cid: $('#cell_id').val(),
                    action:'load_villages'
                    };
            $("#village_id").empty();
            $('#spinner_cell').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_cell').fadeOut('fast');
                    $.each(data, function (index, value) {
                        $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName+"</option>");
                    });
                    $("#village").attr('hidden',false);
                    $("#uBtn").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_cell').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
    });      
</script>