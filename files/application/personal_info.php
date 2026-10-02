<?php
    $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
    $stmt00->execute();
    if($stmt00->rowCount()>0){
        echo '<script>window.location.href = "https://misnjala.edu.sl/applicant/edu?mis=1";</script>';
        exit;
    }
?>
<!-- Start app main Content -->
        <div class="main-content">
                        <section class="section">
                            
                            <div class="section-header">
                                <h3>1. Personal details</h3>
                                <div class="section-header-breadcrumb">
                                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                    <div class="breadcrumb-item"><a href="#">Personal details</a></div>
                                </div>
                            </div>
                            <?php
                                Personalinfo($conn, $acc_id)
                            ?>
                            
                            
                            
                            <div class="section-body">
                                <div class="row" id="profile">
                                    <?php
                                    $stmt=$conn->prepare("SELECT tbl_applicants.*,
                                                                 tbl_nationality.nationality as nat,
                                                                 tbl_country.cntr_name as cname,
                                                                 provinces.provincename as pname,
                                                                 districts.namedistrict as dname,
                                                                 sectors.namesector as sname,
                                                                 cells.namecell as cellname,
                                                                 villages.VillageName as vname
                                                                 FROM tbl_applicants
                                                                 LEFT JOIN tbl_nationality ON
                                                                 tbl_applicants.nationality=tbl_nationality.nat_id
                                                                 LEFT JOIN tbl_country ON
                                                                 tbl_applicants.country=tbl_country.cntr_id
                                                                 LEFT JOIN provinces ON
                                                                 tbl_applicants.province_id=provinces.provincecode
                                                                 LEFT JOIN districts ON
                                                                 tbl_applicants.district_id=districts.districtcode
                                                                 LEFT JOIN sectors ON
                                                                 tbl_applicants.sector=sectors.sectorcode
                                                                 LEFT JOIN cells ON
                                                                 tbl_applicants.cell_id=cells.codecell
                                                                 LEFT JOIN villages ON
                                                                 tbl_applicants.village_id=villages.CodeVillage
                                                                 WHERE tbl_applicants.applicant_id='".$acc_id."'");
                                    $stmt->execute();
                                    $applicantData=$stmt->fetch();
                                    ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Personal information</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse show" id="mycard-collapse">
                                                <div class="card-body">
                                                    <form action="update_form_p" method="POST" id="update_form_p" class="row">
                                                        <input type="hidden" name="action" value="update_personal">
                                                        <input type="hidden" name="stu" value="<?php echo $applicantData['applicant_id']; ?>">
                                                        <input type="hidden" name="code" value="<?php echo $code;?>">
                                                        <div
                                                        <div class="form-group col-md-4">
                                                            <label>Surname (Family name)</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="lname" value="<?php echo $applicantData['lname']; ?>" placeholder="e.g. GASANA" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Middle name</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="mname" value="<?php echo $applicantData['mname']; ?>" placeholder="e.g. John">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>First name</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="fname" value="<?php echo $applicantData['fname']; ?>" placeholder="e.g. Peter" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-8">
                                                            <label>Name(s) on previous records, if different from above</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="prevname" value="<?php echo $applicantData['prevname']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Nationality</label>
                                                            <div class="input-group">
                                                                <select name="nationality"  class="form-control select2" style="width:100%;">
                                                                    <?php
                                                                        $sql_nat=$conn->prepare("SELECT * FROM tbl_nationality");
                                                                        $sql_nat->execute();
                                                                        $i=1;
                                                                        while($nat=$sql_nat->fetch()){
                                                                    ?>
                                                                        <option value="<?php echo $nat['nat_id']; ?>" <?php echo $applicantData['nationality'] == $nat['nat_id']?'selected':''; ?>><?php echo $nat['nationality']; ?> </option>
                                                                        <?php } ?>
                                                                        <option value="0" <?php echo $applicantData['nationality'] == 0?'selected':''; ?>>Others</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>ID/ Passport</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-id-card"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="nid" value="<?php echo $applicantData['ID']; ?>" placeholder="e.g. 1 1990 800 xxxxxxxxxx" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Gender</label>
                                                            <div class="input-group">
                                                                <select name="gender" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                                                    <option value="" disabled selected hidden>Choose One...</option>
                                                                    <option value="M" <?php echo $applicantData['gender']=="M"?"selected":""; ?>>Male</option>
                                                                    <option value="F" <?php echo $applicantData['gender']=="F"?"selected":""; ?>>Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Date of birth</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type="date" class="form-control" name="dob" value="<?php echo $applicantData['dob']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Marital status</label>
                                                            <div class="input-group">
                                                                <select name="marital_status" class="form-control select2" style="width:100%;" placeholder="choose one" required>
                                                                    <option value="" disabled selected hidden>Choose One...</option>
                                                                    <option value="Single" <?php echo $applicantData['marital_status']=="Single"?"selected":""; ?>>Single</option>
                                                                    <option value="Married" <?php echo $applicantData['marital_status']=="Married"?"selected":""; ?>>Married</option>
                                                                    <option value="Widowed" <?php echo $applicantData['marital_status']=="Widowed"?"selected":""; ?>>Widowed</option>
                                                                    <option value="Divorced" <?php echo $applicantData['marital_status']=="Divorced"?"selected":""; ?>>Divorced</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Father's name</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user-tie"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="father_names" value="<?php echo $applicantData['father_names']; ?>" class="form-control" placeholder="father's full names" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Mother's name</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user-tie"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="mother_names" value="<?php echo $applicantData['mother_names']; ?>" class="form-control" placeholder="mother's full names" required>
                                                            </div>
                                                        </div>
                                                        <?php
                            $stmt=$conn->prepare("SELECT * FROM tbl_applicant_church WHERE stu='".$code."'");
                            $stmt->execute();
                            $applicantDatax=$stmt->fetch();
                        ?>
                                                        
                                                <div class="form-group col-md-4">
                                                <label>Religion</label>
                                                <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                    <i class="fas fa-city"></i>
                                                    </div>
                                                </div>
                                                <select class="form-control select2" name="church">
    <option>Select Religion</option>
    <option value="Islam" <?= ($applicantDatax['church'] == 'Islam') ? 'selected' : '' ?>>Islam</option>
    <option value="Christianity" <?= ($applicantDatax['church'] == 'Christianity') ? 'selected' : '' ?>>Christianity</option>
</select>
                                                </div>
                                            </div>
                                                        
                                                        
                                                        </div>
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        <div class="bg-whitesmoke br">
                                                            <button type="submit" class="btn btn-primary ml-5 mb-5"><span id="spinner2_p"></span>&nbsp;<span id="indicator2_p">Save changes</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-12 col-sm-6 col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Current Address</h4>
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
                                                                <th scope="col">Country of Residence:</th>
                                                                <th scope="col"><?php echo $applicantData['cname']; ?></th>
                                                            </tr>
                                                            <?php
                                                            if (isset($applicantData['pname'])) {
                                                                echo '
                                                                <tr>
                                                                    <th scope="col">Province:</th>
                                                                    <th scope="col">' . $applicantData['pname'] . '</th>
                                                                </tr>
                                                                ';
                                                            }
                                                            ?>
                                                            
                                                            <?php
                                                            if (isset($applicantData['dname'])) {
                                                                echo '
                                                                <tr>
                                                                    <th scope="col">Province:</th>
                                                                    <th scope="col">' . $applicantData['dname'] . '</th>
                                                                </tr>
                                                                ';
                                                            }
                                                            ?>
                                                            
                                                            <tr>
                                                                <th scope="col">Street:</th>
                                                                <th scope="col"><?php echo $applicantData['street']; ?></th>
                                                            </tr>
                                                            <!--<tr>-->
                                                            <!--    <th scope="col">Cell:</th>-->
                                                            <!--    <th scope="col"><?php echo $applicantData['cellname']; ?></th>-->
                                                            <!--</tr>-->
                                                            <!--<tr>-->
                                                            <!--    <th scope="col">Village:</th>-->
                                                            <!--    <th scope="col"><?php echo $applicantData['vname']; ?></th>-->
                                                            <!--</tr>-->
                                                        </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                                    <button class="btn btn-primary btn-sm" id="update_a"  data-id="<?php echo $acc_id; ?>"><span id="spinner_a"></span>&nbsp;<span id="indicator_a">update info</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-6">
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
                                                        <table class="table table-sm" style="width: 90%;">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Email:</th>
                                                                <th scope="col"><?php echo $applicantData['email']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Phone:</th>
                                                                <th scope="col"><?php echo $applicantData['phone']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Father's Phone:</th>
                                                                <th scope="col"><?php echo $applicantData['parent_phone']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Mother's Phone:</th>
                                                                <th scope="col"><?php echo $applicantData['ref_phone']; ?></th>
                                                            </tr>
                                                        </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                                    <button class="btn btn-primary btn-sm" id="update_c"  data-id="<?php echo $acc_id; ?>"><span id="spinner_c"></span>&nbsp;<span id="indicator_c">update info</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="row">
                                    <div class="col-12 col-sm-6 col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Applicacant Type</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse21" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse show" id="mycard-collapse21">
                                                <div class="card-body">
                                                    
                                                    <div class="">
                                                        <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Type Of Applicant:</th>
                                                                <th scope="col"><?php echo $applicantData['app_type']; ?></th>
                                                            </tr>
                                                            
                                                            
                                                        </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                                    <button class="btn btn-primary btn-sm" id="update_tc"  data-id="<?php echo $acc_id; ?>"><span id="spinner_ct"></span>&nbsp;<span id="indicator_a">update info </span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Medical & Disability</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse31" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse show" id="mycard-collapse31">
                                                <div class="card-body">
                                                    <div class=" ">
                                                        <table class="table table-sm" style="width: 90%;">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Blood Group:</th>
                                                                <th scope="col"><?php echo $applicantData['blood_group']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Disability Status:</th>
                                                                <th scope="col"><?php echo $applicantData['disability']; ?></th>
                                                            </tr>
                                                            
                                                            <?php
                                                            if ($applicantData['disability'] == "Yes") {
                                                                echo '
                                                                <tr>
                                                                    <th scope="col">Disability Detail:</th>
                                                                    <th scope="col">' . $applicantData['disability_detail'] . '</th>
                                                                </tr>
                                                                ';
                                                            }
                                                            ?>
                                                            
                                                            
                                                        </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="display:flex;flex-direction:row-reverse;">
                                                    <button class="btn btn-primary btn-sm" id="update_tb"  data-id="<?php echo $acc_id; ?>"><span id="spinner_cb"></span>&nbsp;<span id="indicator_c">update info</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Next of kin/ Guardian (to be contacted in case of emergency)</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse-8" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse show" id="mycard-collapse-8">
                                                <div class="card-body">
                                                    <form action="update_form_p0" method="POST" id="update_form_p0" class="row">
                                                        <input type="hidden" name="action" value="update_guardian">
                                                        <input type="hidden" name="stu" value="<?php echo $applicantData['applicant_id']; ?>">
                                                        <div class="form-group col-md-6">
                                                            <label>Name</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="kin_name" value="<?php echo $applicantData['kin_name']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Relationship to applicant</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-users"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="kin_relation" value="<?php echo $applicantData['kin_relation']; ?>" placeholder="e.g. Uncle" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Address</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-map"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="kin_address" value="<?php echo $applicantData['kin_address']; ?>" placeholder="e.g. Freetown" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Email</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-envelope"></i>
                                                                </div>
                                                            </div>
                                                            <input type="email" name="kin_email" value="<?php echo $applicantData['kin_email']; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Phone</label>
                                                            <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                <i class="fas fa-phone"></i>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="kin_tel" value="<?php echo $applicantData['kin_tel']; ?>" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="bg-whitesmoke br">
                                                            <button type="submit" class="btn btn-primary"><span id="spinner2_p0"></span>&nbsp;<span id="indicator2_p0">Save changes</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php ReportProblem($conn, $code, $thing); ?>
                            </div>
                        </section>
                                
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
                                                <input type="email" class="form-control" name="email" id="email" placeholder="e.g. gasana@example.com" readonly required>
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
                                            <hr/>
                                            <div class="form-group" id="prov" style="display:none;">
                                                <label class="text-success"><strong>Your Address</strong></label><br>
                                                <label>Province</label>
                                                <select name="province_id" id="province_id" class="form-control select2" style="width:100%;">
                                                </select><span id="spinner_prov"></span>
                                            </div>
                                            <div class="form-group" id="district" style="display:none;">
                                                <label>District</label>
                                                <select name="district_id" id="district_id" class="form-control select2" style="width:100%;">
                                                    
                                                </select><span id="spinner_dis"></span>
                                            </div>
                                            <div class="form-group" id="sect">
                                                <label id="street_label">Street Number</label>
                                               <input type="text" name="street" id="street" class="form-control">
                                            </div>
                                            <!--<div class="form-group" id="cell">-->
                                            <!--    <label>Cell</label>-->
                                            <!--    <select name="cell_id" id="cell_id" class="form-control select2" style="width:100%;">-->
                                                    
                                            <!--    </select><span id="spinner_cell"></span>-->
                                            <!--</div>-->
                                            <!--<div class="form-group" id="village">-->
                                            <!--    <label>Village</label>-->
                                            <!--    <select name="village_id" id="village_id" class="form-control select2" style="width:100%;">-->
                                                    
                                            <!--    </select>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="modal-footer bg-whitesmoke br">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="uBtn"><span id="spinner2_a"></span>&nbsp;<span id="indicator2_a">Save changes</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end update modal address-->
<!--start update modal type category-->
                            <form action="update_form_tc" method="POST" id="update_form_tc">
                            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_tc">
                                <div class="modal-dialog" role="document">
                                    <input type="hidden" name="action" value="update_type">
                                    <input type="hidden" name="stuat" id="stuat">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Updating Applicant Type </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Type Of Applicant </label>
                                                <select name="app_type" id="app_type" class="form-control select2" style="width:100%;">
                                                    <option value="" disabled>Select Type</option>
                                                    <option value="Regular">Regular</option>
                                                    <option value="Matured">Matured</option>
                                                </select>

                                            </div>
                                            <hr/>
                                            
                                        </div>
                                        <div class="modal-footer bg-whitesmoke br">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="uBtn"><span id="spinner2_a"></span>&nbsp;<span id="indicator2_a">Save changes</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
<!--start update modal blood -->
                            <form action="update_form_tb" method="POST" id="update_form_tb">
                            <div class="modal fade" tabindex="-1" role="dialog" id="updateModal_tb">
                                <div class="modal-dialog" role="document">
                                    <input type="hidden" name="action" value="update_group">
                                    <input type="hidden" name="stuab" id="stuab">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Updating Blood Group & Disability </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Blood Group</label>
                                                <input type="text" class="form-control" name="blood_group" id="blood_group">

                                            </div>
                                            <hr/>
                                            <div class="form-group">
                                                <label>Disability Detail (any Form Of Disability)</label>
                                                <select class="form-control select2" name="disability" id="disability" style="width:100%;">
                                                    <option selected disabled>Select Status</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <div class="additional-fields">
                                            <div class="form-group">
                                                <label>Disability Detail</label>
                                                <input type="text" class="form-control" name="disability_detail" id="disability_detail">
                                            </div>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer bg-whitesmoke br">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="bBtn"><span id="spinner2_b"></span>&nbsp;<span id="indicator2_pb">Save changes</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                        
                        
                    </div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    
    $('.additional-fields').hide();
    
    $('#disability').on('change', function() {
        var selectedValue = $(this).val();
        
        if (selectedValue === 'Yes') {
            // Show fields if 'Yes' is selected
            $('.additional-fields').slideDown();
        } else {
            // Hide fields if 'No' is selected
            $('.additional-fields').slideUp();
        }
    });
    
    //Update Personal
    $("#update_form_p").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2_p').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_p').html("Saving...");
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner2_p').fadeOut('fast');
                $('#indicator2_p').html("Save changes");
                if(data.status==200){
                    window.location.reload();
                    pop_up_success(data.message);
                    $("#updateModal_p").modal('hide');
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
    
    
    //Update guradian
    $("#update_form_p0").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2_p0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_p0').html("Saving...");
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner2_p0').fadeOut('fast');
                $('#indicator2_p0').html("Save changes");
                if(data.status==200){
                    window.location.reload();
                    pop_up_success(data.message);
                    $("#updateModal_p0").modal('hide');
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2_p0').fadeOut('fast');
                $('#indicator2_p0').html("Save changes");
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    //update application type 
    $("#update_form_tc").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2_p0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_p0').html("Saving...");
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner2_p0').fadeOut('fast');
                $('#indicator2_p0').html("Save changes");
                if(data.status==200){
                    window.location.reload();
                    pop_up_success(data.message);
                    $("#updateModal_tc").modal('hide');
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2_p0').fadeOut('fast');
                $('#indicator2_p0').html("Save changes");
                pop_wrong("Something went wrong!");
            }
        });
    });
          
    //blood group
    $("#update_form_tb").submit(function(e){
        e.preventDefault();
    
        var formData = new FormData(this)
        $('#spinner2_p0').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_p0').html("Saving...");
        $.ajax({
            url: "/files/application/application_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(data){
                $('#spinner2_b').fadeOut('fast');
                $('#indicator2_pb').html("Save changes");
                if(data.status==200){
                    window.location.reload();
                    pop_up_success(data.message);
                    $("#updateModal_tb").modal('hide');
                }
                if(data.status==401){
                    pop_wrong(data.message);
                }
                if(data.status==500){
                    pop_wrong(data.message);
                }
            },error: function(){
                $('#spinner2_b').fadeOut('fast');
                $('#indicator2_pb').html("Save changes");
                pop_wrong("Something went wrong!");
            }
        });
    });
    
    //pre-update View contact
    $('#update_c').click(function () {
        var data_id = $(this).data('id');
        var getData= {
                student: data_id,
                action:'view_profile'
                };
        $('#spinner_c').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
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
    
    //pre-update view type,category
    $('#update_tc').click(function () {
    var data_id = $(this).data('id');
    var getData = {
        student: data_id,
        action: 'view_profile'
    };
    $('#spinner_ct').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/application/application_controller.php",
        data: getData,
        dataType: "JSON",
        success: function (data) {
            $('#spinner_ct').fadeOut('fast');
            // Set the value of app_type and make it selected
            $("#app_type").val(data[0].app_type).trigger('change');
            $('#stuat').val(data_id);
            $('#updateModal_tc').modal('show');
        },
        error: function (error) {
            $('#spinner_ct').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});
//pre-update view Blood
    $('#update_tb').click(function () {
    var data_id = $(this).data('id');
    var getData = {
        student: data_id,
        action: 'view_profile'
    };
    $('#spinner_cb').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.ajax({
        type: "POST",
        url: "/files/application/application_controller.php",
        data: getData,
        dataType: "JSON",
        success: function (data) {
            $('#spinner_cb').fadeOut('fast');
            // Set the value of app_type and make it selected
            $("#blood_group").val(data[0].blood_group);
            $("#disability").val(data[0].disability).trigger('change');
            $("#disability_detail").val(data[0].disability_detail);
            $('#stuab').val(data_id);
            $('#updateModal_tb').modal('show');
        },
        error: function (error) {
            $('#spinner_cb').fadeOut('fast');
            pop_wrong("Something went wrong!");
        }
    });
});

        
    //pre-update View address
    $('#update_a').click(function () {
        var data_id = $(this).data('id');
        var getData= {
                student: data_id,
                action:'view_profile'
                };
        $('#spinner_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/application/application_controller.php",
            data: getData,
            dataType:"JSON",
            success:function(data){
                $('#spinner_a').fadeOut('fast');
                $("#stua").val(data_id);
                var selectElement0 = document.getElementById('country');
                var selectElement1 = document.getElementById('province_id');
                var selectElement2 = document.getElementById('district_id');
                // var selectElement3 = document.getElementById('sector');
                // var selectElement4 = document.getElementById('cell_id');
                // var selectElement5 = document.getElementById('village_id');
                $("#province_id").empty();
                $("#district_id").empty();
                // $("#sector").empty();
                // $("#cell_id").empty();
                // $("#village_id").empty();
                $("#province_id").append("<option value='0'></option>");
                $.each(data[1], function (index, value) {
                        $("#province_id").append("<option value='" + value.provincecode + "'>" + value.provincename +"</option>");
                    });
                $.each(data[2], function (index, value) {
                        $("#district_id").append("<option value='" + value.districtcode + "'>" + value.namedistrict +"</option>");
                    });
                // $.each(data[3], function (index, value) {
                //         $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector +"</option>");
                //     });
                // $.each(data[4], function (index, value) {
                //         $("#cell_id").append("<option value='" + value.codecell + "'>" + value.nameCell +"</option>");
                //     });
                // $.each(data[5], function (index, value) {
                //         $("#village_id").append("<option value='" + value.CodeVillage + "'>" + value.VillageName +"</option>");
                //     });
                
                // Set selected values
                var selectedOption1 = selectElement1.querySelector('option[value="' + data[0].province_id + '"]');
                var selectedOption2 = selectElement2.querySelector('option[value="' + data[0].district_id + '"]');
                // var selectedOption3 = selectElement3.querySelector('option[value="' + data[0].sector + '"]');
                // var selectedOption4 = selectElement4.querySelector('option[value="' + data[0].cell_id + '"]');
                // var selectedOption5 = selectElement5.querySelector('option[value="' + data[0].village_id + '"]');
                if(selectedOption1){
                    selectedOption1.selected = true;
                    selectedOption2.selected = true;
                    // selectedOption3.selected = true;
                    // selectedOption4.selected = true;
                    // selectedOption5.selected = true;
                    
                    selectElement1.prepend(selectedOption1);
                    selectElement2.prepend(selectedOption2);
                    // selectElement3.prepend(selectedOption3);
                    // selectElement4.prepend(selectedOption4);
                    // selectElement5.prepend(selectedOption5);
                }
                var selectedOption0 = selectElement0.querySelector('option[value="' + data[0].country + '"]');
                selectedOption0.selected = true;
                selectElement0.prepend(selectedOption0);
                $('#street').val(data[0].street || '');
                // Trigger change on country to show/hide province/district appropriately
                toggleCountryAddressFields(data[0].country);
                $('#updateModal_a').modal('show');
			},
			error:function(error){
			    $('#spinner_a').fadeOut('fast');
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
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_c').fadeOut('fast');
                    $('#indicator2_c').html("Save changes");
                    if(data.status==200){
                        window.location.reload();
                        pop_up_success(data.message);
                        $("#updateModal_c").modal('hide');
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
          
    // Show/hide Province & District based on country (167 = Sierra Leone)
    function toggleCountryAddressFields(countryVal) {
        if (String(countryVal) === '167') {
            $('#prov').show();
            $('#district').show();
            $('#street_label').text('Street');
            $('#province_id').prop('required', true);
            $('#district_id').prop('required', true);
            $('#street').prop('required', false);
        } else {
            $('#prov').hide();
            $('#district').hide();
            $('#street_label').text('Street Number');
            try {
                $('#province_id').val(null).trigger('change');
            } catch (e) {}
            try {
                $('#district_id').val(null).trigger('change');
            } catch (e) {}
            $('#province_id').prop('required', false);
            $('#district_id').prop('required', false);
            $('#street').prop('required', true);
        }
    }

    $('#country').change(function () {
        toggleCountryAddressFields($(this).val());
    });

    //Update address
    $("#update_form_a").submit(function(e){
            e.preventDefault();

        var countryVal = $('#country').val();
        if (String(countryVal) !== '167' && !$.trim($('#street').val())) {
            pop_wrong('Please enter your Street Number.');
            return;
        }
        if (String(countryVal) === '167') {
            var prov = $('#province_id').val();
            var dist = $('#district_id').val();
            if (!prov || prov === '0' || !dist || dist === '0') {
                pop_wrong('Please select Province and District.');
                return;
            }
        }
    
        var formData = new FormData(this)
        $('#spinner2_a').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_a').html("Saving...");
            $.ajax({
                url: "/files/application/application_controller.php",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data){
                    $('#spinner2_a').fadeOut('fast');
                    $('#indicator2_a').html("Save changes");
                    if(data.status==200){
                        window.location.reload();
                        pop_up_success(data.message);
                        $("#updateModal_a").modal('hide');
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

        //load districts
        $('#province_id').change(function () {
            // $("#uBtn").attr('hidden', true);
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
            // $("#uBtn").attr('hidden', true);
            var getData= {
                    did: $('#district_id').val(),
                    action:'load_sectors'
                    };
            $("#sector").empty();
            $('#spinner_dis').html("<img src='/img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "/files/application/application_controller.php",
                data: getData,
                dataType:"JSON",
                success:function(data){
                    $('#spinner_dis').fadeOut('fast');
                    $("#sector").append("<option></option>");
                    $.each(data, function (index, value) {
                        $("#sector").append("<option value='" + value.sectorcode + "'>" + value.namesector+"</option>");
                    });
                    $("#sect").attr('hidden',false);

				},
				error:function(error){
				    $('#spinner_dis').fadeOut('fast');
                    pop_wrong("Something went wrong!"); 
				}
            });
        });
        
        //load cells
        $('#sector').change(function () {
            $("#uBtn").attr('hidden', true);
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