<style>
textarea {
    width: 100%;
    height: 150px;
    padding: 10px;
    border-radius: 4px;
    font-size: 16px;
    margin-bottom: 20px;
    border: 1px solid grey;
}

textarea:focus {
    border: 1px solid grey;
    box-shadow: none;
}
</style>

<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo $_GET['app']; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Application</a></div>
            </div>
        </div>
    </section>
    <section class="section">
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
                                                 WHERE tbl_applicants.code='".$_GET['app']."' ");
                    $stmt->execute();
                    $applicantData=$stmt->fetch();
                    ?>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">First name:</th>
                                                <th scope="col"><?php echo $applicantData['fname']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Middle name:</th>
                                                <th scope="col"><?php echo $applicantData['mname']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Last name:</th>
                                                <th scope="col"><?php echo $applicantData['lname']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Name(s) on previous records:</th>
                                                <th scope="col"><?php echo $applicantData['prevname']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Gender:</th>
                                                <th scope="col">
                                                    <?php echo $applicantData['gender']=="M"?"Male":"Female"; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Marital status:</th>
                                                <th scope="col"><?php echo $applicantData['marital_status']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Nationality:</th>
                                                <th scope="col">
                                                    <?php echo $applicantData['nationality']!=0?$applicantData['nat']:'Others'; ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">ID/passport:</th>
                                                <th scope="col"><?php echo $applicantData['ID']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Father:</th>
                                                <th scope="col"><?php echo $applicantData['father_names']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Mother:</th>
                                                <th scope="col"><?php echo $applicantData['mother_names']; ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Address and contact information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Country:</th>
                                                <th scope="col"><?php echo $applicantData['cname']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Province:</th>
                                                <th scope="col">
                                                    <?php echo $applicantData['province_id']!=0?$applicantData['pname']:'N/A'; ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">District:</th>
                                                <th scope="col">
                                                    <?php echo $applicantData['district_id']!=0?$applicantData['dname']:'N/A'; ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Street:</th>
                                                <th scope="col">
                                                    <?php echo $applicantData['street']!=0?$applicantData['street']:'N/A'; ?>
                                                </th>
                                            </tr>

                                            <tr>
                                                <th scope="col">Email:</th>
                                                <th scope="col"><?php echo $applicantData['email']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Phone:</th>
                                                <th scope="col"><?php echo $applicantData['phone']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Parent's Phone:</th>
                                                <th scope="col"><?php echo $applicantData['parent_phone']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Second Phone:</th>
                                                <th scope="col"><?php echo $applicantData['ref_phone']; ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Next of kin/ Guardian</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse31" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse31">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name:</th>
                                                <th scope="col"><?php echo $applicantData['kin_name']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Relationship:</th>
                                                <th scope="col"><?php echo $applicantData['kin_relation']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Address:</th>
                                                <th scope="col"><?php echo $applicantData['kin_address']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Email:</th>
                                                <th scope="col"><?php echo $applicantData['kin_email']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Phone:</th>
                                                <th scope="col"><?php echo $applicantData['kin_tel']; ?></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                        $stmt=$conn->prepare("SELECT c.*, cnt.cntr_name FROM tbl_applicant_church c INNER JOIN tbl_country cnt ON c.country = cnt.cntr_id WHERE c.stu='".$_GET['app']."'");
                        $stmt->execute();
                        $applicantchurch=$stmt->fetch();
                    ?>

                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Religion</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse32" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse32">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Country:</th>
                                                <th scope="col"><?php echo $applicantchurch['cntr_name']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Religion:</th>
                                                <th scope="col"><?php echo $applicantchurch['church']; ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="col">City:</th>
                                                <th scope="col"><?php echo $applicantchurch['city']; ?></th>
                                            </tr>

                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Previous Education</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse33" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse33">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-none" id="datatable-default">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Institution Name</th>
                                                <th>Joined From</th>
                                                <th>Ended</th>
                                                <th>Diploma/ Certificate</th>
                                                <th>Award obtained</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                    								$stmt = $conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu = '".$_GET['app']."' ORDER BY id DESC ");
                    								try {
                    									$stmt->execute(array());
                										$i = 1;
                										while ($row = $stmt->fetch()) {
                								?>

                                            <tr class="gradeX">
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['school']; ?></td>
                                                <td><?php echo $row['year_from']; ?></td>
                                                <td><?php echo $row['year_to']; ?></td>
                                                <td><?php echo $row['certificate']; ?></td>
                                                <td><?php echo $row['award']; ?></td>
                                            </tr>

                                            <?php
                    							    }
                    								}catch (PDOException $ex) {
                									   echo "Couldn't find records";
                								}
                								?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Payment Information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse333" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse333">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-none" id="datatable-default">
                                        <thead>
                                            <tr>

                                                <th>Voucher Number </th>
                                                <th>Payment Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                    								$stmtV = $conn->prepare("SELECT slip_no,recorded_date FROM payment_trial WHERE reg_no = '".$_GET['app']."'");
                    								try {
                    									$stmtV->execute();
                										$i = 1;
                										while ($rowV = $stmtV->fetch()) {
                								?>

                                            <tr class="gradeX">
                                                <td><?php echo $rowV['slip_no']; ?></td>
                                                <td><?php echo $rowV['recorded_date']; ?></td>
                                            </tr>

                                            <?php
                    							    }
                    								}catch (PDOException $ex) {
                									   echo "Couldn't find records";
                								}
                								?>
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
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Submitted applications</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-app" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse-app">
                            <div class="card-body" id="applications">
                                <?php
                                    $sql=$conn->prepare("SELECT
                                                            tbl_admittedPRG.*,
                                                            tbl_campus.camp_full_name,
                                                            tbl_program_type.prg_type_full_name,
                                                            tbl_faculty.fac_full_name,
                                                            tbl_department.dept_full_name,
                                                            tbl_specialization.splz_full_name,
                                                            tbl_program_mode.prg_mode_full_name,
                                                            tbl_level.level_full_name
                                                        FROM
                                                            tbl_admittedPRG
                                                            INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                            INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                            INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id=tbl_faculty.fac_id
                                                            INNER JOIN tbl_department ON tbl_admittedPRG.dept_id=tbl_department.dept_id
                                                            INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                            INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                            INNER JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                        WHERE
                                                             tbl_admittedPRG.Stu_code = '".$_GET['app']."' AND tbl_admittedPRG.sts in (2,3)
                                                            ");
                                    $sql->execute();
                                    $i=1;
                                    $has_pending = false;
                                    while($apps=$sql->fetch()){
                                        if($apps['sts']==2) $has_pending = true;
                                ?>
                                <div class="row application-row"
                                    style="border-radius:5px;margin-bottom:10px;<?php echo $apps['sts']==3?'border: 2px solid green':'border: 2px solid grey'; ?>">

                                    <?php if($apps['sts']==2){ ?>
                                    <div class="form-group col-12 col-md-1 col-lg-1"
                                        style="display: flex; align-items: center; justify-content: center;">
                                        <input type="checkbox" class="app-checkbox"
                                            value="<?php echo $apps['Aprg_id']; ?>"
                                            style="width: 20px; height: 20px; cursor: pointer;">
                                    </div>
                                    <?php } else { ?>
                                    <div class="form-group col-12 col-md-1 col-lg-1"
                                        style="display: flex; align-items: center; justify-content: center;">
                                        <span class="badge badge-success">Admitted</span>
                                    </div>
                                    <?php } ?>

                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Campus</div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['camp_full_name']; ?></b></a></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job"><b>Program </b></div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['prg_type_full_name']; ?></b></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job"><b>School </b></div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['fac_full_name']; ?></b></a></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job"><b>Department</b></div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['dept_full_name']; ?></b></a></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job"><b>Specialization </b></div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['splz_full_name']; ?></b></a></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Level</div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['level_full_name']; ?></b></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Learning Mode</div>
                                            <div class="user-detail-name"><a
                                                    href="#"><b><?php echo $apps['prg_mode_full_name']; ?></b></a>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <?php } ?>

                                <!-- Action Buttons Section -->
                                <?php if($has_pending){ ?>
                                <div class="row"
                                    style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> <strong>Instructions:</strong>
                                            <ul style="margin-bottom: 0; margin-top: 10px;">
                                                <li><strong>Admit:</strong> Select one application to admit the
                                                    student. This will mark the application as admitted (status 3). All
                                                    other applications for this student will be rejected. The full
                                                    registration process (creating student records, generating
                                                    registration numbers, etc.) will be done separately.</li>
                                                <li><strong>Reject:</strong> Select one application to reject with a
                                                    reason. The applicant will be notified via email.</li>
                                                <li><strong>Update:</strong> Select one application to update program
                                                    details (campus, program type, faculty, department,
                                                    specialization).</li>
                                            </ul>
                                            <div
                                                style="margin-top: 10px; padding: 10px; background-color: #e4cd83ff; border-left: 4px solid #ffc107;">
                                                <strong><i class="fas fa-exclamation-triangle"></i>
                                                    Important:</strong>
                                                You are reviewing applications that have been forwarded by Deans. When
                                                you admit an application, it will be marked as admitted but the full
                                                registration process (generating student ID, creating accounts, etc.)
                                                will be handled separately. You can only process applications that have
                                                been forwarded to the Registrar's Office (status 2).
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12"
                                        style="display:flex; justify-content:center; gap: 10px; flex-wrap: wrap;">
                                        <button class="btn btn-success btn-sm" id="admitBtn" disabled>
                                            <span id="spinneradmit"></span>&nbsp;<span id="indicatoradmit">Admit
                                                Application</span>
                                        </button>
                                        <button class="btn btn-danger btn-sm" id="rejectBtn" disabled>
                                            <span id="spinnerrej"></span>&nbsp;<span id="indicatorrej">Reject
                                                Application</span>
                                        </button>
                                        <button class="btn btn-warning btn-sm" id="updateBtn" disabled>
                                            <span id="spinnerupd"></span>&nbsp;<span id="indicatorupd">Update Program
                                                Details</span>
                                        </button>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    $checkEssay=$conn->prepare("SELECT * FROM  tbl_applicant_essays WHERE stu='".$_GET['app']."'");
    $checkEssay->execute();
    $essay=$checkEssay->rowCount();
    $data_ess=$checkEssay->fetch();
    if($essay>0){
        ?>
    <section class="section">
        <div class="section-body">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Research proposal </h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse_research" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse_research">
                            <div class="card-body">
                                <h6>Please provide a detailed research proposal for your intended study. Your proposal
                                    should include the title, introduction, objectives, literature review, methodology,
                                    expected outcomes, timeline, and references. Ensure that your proposal is
                                    well-organized and thoroughly addresses each section.</h6>
                                <div class="form-group">
                                    <textarea name="essay" class="essay"
                                        readonly><?php echo $data_ess['essay'] ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php }
    ?>


    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Documents</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-doc" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse-doc">
                            <div class="card-body row">

                                <?php
                                    $sql2=$conn->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = '".$_GET['app']."'");
                                    $sql2->execute();
                                    $i=1;
                                    while($docs=$sql2->fetch()){
                                        $string=$docs['upload_doc'];
                                        $parts = explode('/', $string);
                                        $after_slash = end($parts);
                                        $parts = explode('_', $after_slash);
                                        $before_underscore = reset($parts);
                                        
                                        $isql2=$conn->prepare("SELECT * FROM tbl_document_type WHERE file_name = '".$before_underscore."'");
                                        $isql2->execute();
                                        $moreInfo=$isql2->fetch();
                                        ?>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <article class="article">
                                        <div class="article-header">
                                            <div class="article-image"
                                                data-background="../..<?php echo $moreInfo['file_type']=="image"?$docs['upload_doc']:"/student_docs/pdf.png"; ?>">
                                            </div>
                                            <div class="article-title">
                                                <h2><a href="../..<?php echo $docs['upload_doc']; ?>"
                                                        target="_blank"><?php echo $moreInfo['document_name'] ?></a>
                                                </h2>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Reject modal-->
    <form action="reject_form" method="POST" id="reject_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="rejModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejecting Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ad_m_id" id="ad_m_id">
                        <input type="hidden" name="action" value="reject">
                        <div class="form-group">
                            <label>Reason</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        &nbsp;<i class="fas fa-info"></i>&nbsp;
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="reason"
                                    placeholder="your reason goes here" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="r_appBtn"><span
                                id="spinner2"></span>&nbsp;<span id="indicator2">Reject</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal-->

    <!--Update course modal-->
    <form action="Update_spec_form" method="POST" id="Update_spec_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="specModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Program Application updating</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ad_m_id_spec" id="ad_m_id_spec">
                        <input type="hidden" name="action" value="change_course_picker">
                        <div class="row">
                            <div class="form-group col-12 col-sm-12 col-lg-6">
                                <label>Campus</label>
                                <select class="form-control select2" style="width:100%" name="campus_id_app"
                                    id="campus_id_app" required>

                                    <?php
                                    $sql_campus=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_active=1");
                                    $sql_campus->execute();
                                    $i=1;
                                    while($campus=$sql_campus->fetch()){
                                        ?>
                                    <option value="<?php echo $campus['camp_id']; ?>">
                                        <?php echo $campus['camp_full_name'] ?> </option>
                                    <?php } ?>
                                </select>
                                &nbsp;<span id="spinner_prg"></span>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-6">
                                <label>Program Type</label>
                                <select class="form-control select2" style="width:100%" name="p_type_spec"
                                    id="p_type_spec" required>

                                    <?php
                                $select_prg=$conn->prepare("SELECT  tbl_program_type.*,tbl_campus.camp_full_name FROM 
                                tbl_program_type INNER JOIN tbl_campus ON tbl_program_type.campus_id=tbl_campus.camp_id WHERE tbl_program_type.status=1");
                                $select_prg->execute();
                                while($selectPrg=$select_prg->fetch()){
                                    ?>
                                    <option value="<?php echo $selectPrg['prg_type_id']; ?>">
                                        <?php echo $selectPrg['prg_type_full_name'].' | '.$selectPrg['camp_full_name']?>
                                    </option>
                                    <?php }
                                ?>
                                </select>
                                &nbsp;<span id="spinner0_spec_prg"></span>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-6" id="fct_spec_ed" style="display:none">
                                <label>Faculty</label>
                                <select class="form-control select2" style="width:100%" name="fac_id_spec"
                                    id="fac_id_spec">

                                </select>
                                <span id="spinner00_fac_sp"></span>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-6" id="dept_spec_ed" style="display:none">
                                <label>Department</label>
                                <select class="form-control select2" style="width:100%" name="dept_id_spec"
                                    id="dept_id_spec">
                                </select>
                                <span id="spinner00_dep_sp"></span>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <label>Specialization</label>
                                <select class="form-control select2" style="width:100%" name="spec_id_up"
                                    id="spec_id_up" required>

                                    <?php
                                $select_spec=$conn->prepare("SELECT tbl_specialization.splz_full_name,tbl_specialization.splz_id FROM 
                                tbl_specialization INNER JOIN tbl_program_type ON tbl_program_type.prg_type_id=tbl_specialization.prg_type WHERE tbl_specialization.status=1");
                                $select_spec->execute();
                                while($selectSpec=$select_spec->fetch()){
                                    ?>
                                    <option value="<?php echo $selectSpec['splz_id']; ?>">
                                        <?php echo $selectSpec['splz_full_name'];?> </option>
                                    <?php }
                                ?>
                                </select>
                                &nbsp;<span id="spinner0_spec"></span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="r_appBtnSpec"><span
                                id="spinner2_spec"></span>&nbsp;<span id="indicator2_spec">Save changes</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <form action="admit_student_with_file" id="admit_student_with_file" method="POST" enctype="multipart/form-data">
        <div class="modal fade" tabindex="-1" role="dialog" id="uploadModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose file</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body pb-0 row">
                            <div class="form-group  col-12">
                                <input type="hidden" name="action" value="admin_with_file">
                                <input type="hidden" name="admId" id="admId">
                                <input class="form-control" type="file" name="adm_letter" id="image-file" accept="pdf/*"
                                    required>
                            </div>
                            <div class="form-group col-12" style="margin:auto; max-height:300px; margin-bottom:20px;">
                                <img id="cropped-image">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button id="crop-button" class="btn btn-primary btn-sm" type="submit"><span
                                id="spinner200"></span>&nbsp;<span id="indicator200">Send & Save</span></button>
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
$(document).ready(function() {
    // Handle checkbox selection - only allow one checkbox to be selected
    $('.app-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            // Uncheck all other checkboxes
            $('.app-checkbox').not(this).prop('checked', false);
            // Enable buttons
            $('#admitBtn, #rejectBtn, #updateBtn').prop('disabled', false);
        } else {
            // If unchecked and no other checkbox is checked, disable buttons
            if ($('.app-checkbox:checked').length === 0) {
                $('#admitBtn, #rejectBtn, #updateBtn').prop('disabled', true);
            }
        }
    });

    // Admit application (simple version)
    $("#admitBtn").click(function(e) {
        e.preventDefault();
        var selectedApp = $('.app-checkbox:checked').val();

        if (!selectedApp) {
            pop_wrong("Please select an application first!");
            return;
        }

        var formData = {
            ad_m_i: selectedApp,
            action: 'simple_admit'
        }

        swal({
            title: "Are you sure?",
            text: "This will mark the application as ADMITTED (status 3). All other applications for this student will be rejected. The full registration process will be done separately by the Registrar's Office.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinneradmit').html("<img src='../../img/ajax_loader.gif' width='15'>")
                    .fadeIn('fast');
                $('#indicatoradmit').html("processing...");
                $('#admitBtn').prop('disabled', true);
                $.ajax({
                    url: "/files/admission/admission_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data) {
                        $('#spinneradmit').fadeOut('fast');
                        $('#indicatoradmit').html("Admit Application");
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                        if (data.status == 400 || data.status == 403) {
                            pop_wrong(data.message);
                            $('#admitBtn').prop('disabled', false);
                        }
                    },
                    error: function() {
                        $('#spinneradmit').fadeOut('fast');
                        $('#indicatoradmit').html("Admit Application");
                        $('#admitBtn').prop('disabled', false);
                        pop_wrong("Something went wrong!");
                    }
                });
            }
        });
    });

    // Reject application
    $("#rejectBtn").click(function(e) {
        e.preventDefault();
        var selectedApp = $('.app-checkbox:checked').val();

        if (!selectedApp) {
            pop_wrong("Please select an application first!");
            return;
        }

        // Set the application ID in the modal
        $('#ad_m_id').val(selectedApp);
        $('#rejModal').modal('show');
    });

    // Update program details
    $("#updateBtn").click(function(e) {
        e.preventDefault();
        var selectedApp = $('.app-checkbox:checked').val();

        if (!selectedApp) {
            pop_wrong("Please select an application first!");
            return;
        }

        $("#ad_m_id_spec").val(selectedApp);
        var formdata = {
            app: selectedApp,
            action: "load_program_applic"
        };
        $.ajax({
            type: "POST",
            url: "/files/admission/admission_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                var appl_campus = $("#campus_id_app");
                appl_campus.val(data.cump_id);
                appl_campus.prepend(appl_campus.find("option[value='" + data.cump_id +
                    "']"));

                var p_type = $("#p_type_spec");
                p_type.val(data.prg_type);
                p_type.prepend(p_type.find("option[value='" + data.prg_type + "']"));
                var spec = $("#spec_id_up");
                spec.val(data.dept_id);
                spec.prepend(spec.find("option[value='" + data.dept_id + "']"));
            },
            error: function(error) {
                pop_wrong("Something went wrong!");
            }
        });

        $("#specModal").modal('show');
    });

    $(".admits").click(function() {
        var app = $(this).data('id');
        $("#admId").val(app);
        $("#uploadModal").modal('show');
    })

    $("#admit_student_with_file").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this)
        swal({
            title: "Are you sure?",
            text: "This operation is not reversible!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner200').html("<img src='../../img/ajax_loader.gif' width='15'>")
                    .fadeIn('fast');
                $('#indicator200').html("sending...");
                $.ajax({
                    url: "/files/admission/admission_controller2.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#spinner200').fadeOut('fast');
                        $('#indicator200').html("Send & Save");
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            $('#applications').load(location.href +
                                " #applications");
                            $("#uploadModal").modal('hide');
                        }
                        if (data.status == 401) {
                            pop_wrong(data.message);
                        }
                        if (data.status == 500) {
                            pop_wrong(data.message);
                        }
                    },
                    error: function() {
                        $('#spinner200').fadeOut('fast');
                        $('#indicator200').html("Send & Save");
                        pop_wrong("Something went wrong!");
                    }
                });
            } else {
                swal("Operation cancelled!!");
            }
        });
    });

    $(".reject").click(function(e) {
        var app = $(this).data('id');
        $("#ad_m_id").val(app);
        $("#rejModal").modal('show');
    });

    //   new seetings
    $(".update_sepc").click(function(e) {
        var app = $(this).data('id');
        $("#ad_m_id_spec").val(app);
        var formdata = {
            app: app,
            action: "load_program_applic"
        };
        $.ajax({
            type: "POST",
            url: "/files/admission/admission_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                var appl_campus = $("#campus_id_app");
                appl_campus.val(data.cump_id);
                appl_campus.prepend(appl_campus.find("option[value='" + data.cump_id +
                    "']"));

                var p_type = $("#p_type_spec");
                p_type.val(data.prg_type);
                p_type.prepend(p_type.find("option[value='" + data.prg_type + "']"));
                var spec = $("#spec_id_up");
                spec.val(data.dept_id);
                spec.prepend(spec.find("option[value='" + data.dept_id + "']"));
            },
            error: function(error) {

                pop_wrong("Something went wrong!");
            }

        });

        $("#specModal").modal('show');
    });

    // change campus
    $("#campus_id_app").change(function() {
        $('#spinner_prg').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $("#p_type_spec").empty();
        var camp_id = $("#campus_id_app").val();
        var formData = {
            camp_id: camp_id,
            action: "get_program_type"
        }
        $.ajax({
            url: "/files/Faculties/faculty_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(data) {
                $('#spinner_prg').fadeOut('fast');
                $("#p_type_spec").append("<option></option>")
                $.each(data, function(index, value) {
                    $("#p_type_spec").append("<option value='" + value.prg_type_id +
                        "'>" + value.prg_type_full_name + "</option>");
                });
            },
            error: function() {
                $('#spinner_prg').fadeOut('fast');
                pop_wrong("Something went wrong!");

            }
        });
    })

    //load updating faculties
    $("#p_type_spec").change(function() {
        var p_type = $("#p_type_spec").val();
        var formdata = {
            type: p_type,
            action: "load_faculties"
        };
        $('#spinner0_spec_prg').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Programs/program_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner0_spec_prg').fadeOut('fast');
                $('#fct_spec_ed').css({
                    'display': 'block'
                });
                $("#fac_id_spec").empty();
                if (data.length > 0) {
                    $("#fac_id_spec").append("<option></option>");
                    $.each(data, function(index, value) {
                        $("#fac_id_spec").append("<option value='" + value.fac_id +
                            "'>" + value.fac_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner0').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }

        });
    });

    //load updating departments
    $("#fac_id_spec").change(function() {
        var fac_id = $("#fac_id_spec").val();
        var formdata = {
            fac: fac_id,
            action: "load_departments"
        };
        $('#spinner00_fac_sp').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $('#dept_spec_ed').css({
            'display': 'none'
        });
        $.ajax({
            type: "POST",
            url: "/files/Faculties/faculty_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner00_fac_sp').fadeOut('fast');
                $('#dept_spec_ed').css({
                    'display': 'block'
                });
                $("#dept_id_spec").empty();
                if (data.length > 0) {
                    $("#dept_id_spec").append("<option></option>");
                    $.each(data, function(index, value) {
                        $("#dept_id_spec").append("<option value='" + value
                            .dept_id + "'>" + value.dept_full_name + "</option>"
                        );
                    });
                }
            },
            error: function(error) {
                $('#spinner00_fac_sp').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }

        });
    });
    //load updating departments
    $("#fac_id_spec").change(function() {
        var fac_id = $("#fac_id_spec").val();
        var formdata = {
            fac: fac_id,
            action: "load_departments"
        };
        $('#spinner00_fac_sp').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $('#dept_spec_ed').css({
            'display': 'none'
        });
        $.ajax({
            type: "POST",
            url: "/files/Faculties/faculty_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner00_fac_sp').fadeOut('fast');
                $('#dept_spec_ed').css({
                    'display': 'block'
                });
                $("#dept_id_spec").empty();
                if (data.length > 0) {
                    $("#dept_id_spec").append("<option></option>");
                    $.each(data, function(index, value) {
                        $("#dept_id_spec").append("<option value='" + value
                            .dept_id + "'>" + value.dept_full_name + "</option>"
                        );
                    });
                }
            },
            error: function(error) {
                $('#spinner00_fac_sp').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }

        });
    });
    //load updating specilization
    $("#dept_id_spec").change(function() {
        var dep_id = $("#dept_id_spec").val();
        var formdata = {
            dep_id: dep_id,
            action: "load_spec"
        };
        $('#spinner00_dep_sp').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
        $.ajax({
            type: "POST",
            url: "/files/Faculties/faculty_controller.php",
            data: formdata,
            dataType: "JSON",
            success: function(data) {
                $('#spinner00_dep_sp').fadeOut('fast');
                $("#spec_id_up").empty();
                if (data.length > 0) {
                    $("#spec_id_up").append("<option></option>");
                    $.each(data, function(index, value) {
                        $("#spec_id_up").append("<option value='" + value.splz_id +
                            "'>" + value.splz_full_name + "</option>");
                    });
                }
            },
            error: function(error) {
                $('#spinner00_dep_sp').fadeOut('fast');
                pop_wrong("Something went wrong!");
            }

        });
    });
    //reject application
    $("#reject_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this)
        swal({
            title: "Are you sure?",
            text: "This operation is not reversible!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn(
                    'fast');
                $('#indicator2').html("Rejecting...");
                $.ajax({
                    url: "/files/admission/admission_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Reject");
                        if (data.status == 200) {
                            pop_up_success(data.message);
                            $('#applications').load(location.href +
                                " #applications");
                        }
                        if (data.status == 500) {
                            pop_wrong(data.message);
                        }
                    },
                    error: function() {
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Reject");
                        pop_wrong("Something went wrong!");
                    }
                });
            } else {
                swal("Operation cancelled!!");
            }
        });
    });
    $("#Update_spec_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this)
        $('#spinner2_spec').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
        $('#indicator2_spec').html("Changing...");
        $.ajax({
            url: "/files/admission/admission_controller.php",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(data) {
                $('#spinner2_spec').fadeOut('fast');
                $('#indicator2_spec').html("Save changes");
                if (data.status == 200) {
                    pop_up_success(data.message);
                    $('#applications').load(location.href + " #applications");
                    $("#specModal").modal('hide');
                }
                if (data.status == 500) {
                    pop_wrong(data.message);
                }
            },
            error: function() {
                $('#spinner2').fadeOut('fast');
                $('#indicator2').html("Save changes");
                pop_wrong("Something went wrong!");
            }
        });
    })

    $("#acceptance").click(function() {
        var student = $(this).data('id');
        const pageURL = "/files/application/acceptance?k=" + student;
        var left = (screen.width - 800) / 2;
        var top = (screen.height - 600) / 4;
        window.open(pageURL, "Admission Letter",
            'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=800, height=600, top=' +
            top + ', left=' + left);
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