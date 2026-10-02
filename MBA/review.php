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
<?php


$app_code = isset($_GET['app']) ? $_GET['app'] : '';
if (empty($app_code)) {
    echo "<div class='alert alert-danger'>No applicant code provided.</div>";
    exit;
}
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3><?php echo htmlspecialchars($app_code); ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="index.php">Applicants</a></div>
                <div class="breadcrumb-item">Application</div>
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
                                                 WHERE tbl_applicants.code=?");
                    $stmt->execute([$app_code]);
                    $applicantData=$stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$applicantData) {
                        echo "<div class='col-12'><div class='alert alert-warning'>Applicant not found.</div></div>";
                    } else {
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
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>First name:</th>
                                            <th><?php echo $applicantData['fname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Middle name:</th>
                                            <th><?php echo $applicantData['mname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Last name:</th>
                                            <th><?php echo $applicantData['lname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Name(s) on previous records:</th>
                                            <th><?php echo $applicantData['prevname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <th><?php echo $applicantData['gender']=="M"?"Male":"Female"; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Marital status:</th>
                                            <th><?php echo $applicantData['marital_status']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Nationality:</th>
                                            <th><?php echo $applicantData['nationality']!=0?$applicantData['nat']:'Others'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>ID/passport:</th>
                                            <th><?php echo $applicantData['ID']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Father:</th>
                                            <th><?php echo $applicantData['father_names']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Mother:</th>
                                            <th><?php echo $applicantData['mother_names']; ?></th>
                                        </tr>
                                    </thead>
                                </table>
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
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Country:</th>
                                            <th><?php echo $applicantData['cname']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Province:</th>
                                            <th><?php echo $applicantData['province_id']!=0?$applicantData['pname']:'N/A'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <th><?php echo $applicantData['district_id']!=0?$applicantData['dname']:'N/A'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Street:</th>
                                            <th><?php echo $applicantData['street']!=0?$applicantData['street']:'N/A'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <th><?php echo $applicantData['email']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <th><?php echo $applicantData['phone']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Parent's Phone:</th>
                                            <th><?php echo $applicantData['parent_phone']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Second Phone:</th>
                                            <th><?php echo $applicantData['ref_phone']; ?></th>
                                        </tr>
                                    </thead>
                                </table>
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
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name:</th>
                                            <th><?php echo $applicantData['kin_name']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Relationship:</th>
                                            <th><?php echo $applicantData['kin_relation']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <th><?php echo $applicantData['kin_address']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <th><?php echo $applicantData['kin_email']; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <th><?php echo $applicantData['kin_tel']; ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    $stmt2=$conn->prepare("SELECT c.*, cnt.cntr_name FROM tbl_applicant_church c INNER JOIN tbl_country cnt ON c.country = cnt.cntr_id WHERE c.stu=?");
                    $stmt2->execute([$app_code]);
                    $applicantchurch=$stmt2->fetch(PDO::FETCH_ASSOC);
                ?>

                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Religious Information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse32" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse32">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Religion:</th>
                                            <th><?php echo $applicantchurch ? $applicantchurch['church'] : 'N/A'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <th><?php echo $applicantchurch ? $applicantchurch['cntr_name'] : 'N/A'; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <th><?php echo $applicantchurch ? $applicantchurch['city'] : 'N/A'; ?></th>
                                        </tr>
                                    </thead>
                                </table>
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
                                                $stmtEdu = $conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu = ? ORDER BY id DESC");
                                                $stmtEdu->execute([$app_code]);
                                                $i = 1;
                                                while ($row = $stmtEdu->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $row['school']; ?></td>
                                                <td><?php echo $row['year_from']; ?></td>
                                                <td><?php echo $row['year_to']; ?></td>
                                                <td><?php echo $row['certificate']; ?></td>
                                                <td><?php echo $row['award']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?php
                            $stst02=$conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu=? LIMIT 1");
                            $stst02->execute([$app_code]);
                            $data02=$stst02->fetch(PDO::FETCH_ASSOC);
                            if($data02 && !empty($data02['waec_result1'])){
                            ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Scratch card number for first result:</th>
                                                    <th><?php echo $data02['waec_result1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Pin for scratch card result 1:</th>
                                                    <th><?php echo $data02['pin1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>First Exam Year:</th>
                                                    <th><?php echo $data02['first_exam_year']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Examination Body:</th>
                                                    <th><?php echo $data02['exam_body1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Other examination body name:</th>
                                                    <th><?php echo $data02['other_body_name1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Examination Type:</th>
                                                    <th><?php echo $data02['exam_type1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Other Examination Type:</th>
                                                    <th><?php echo $data02['other_exm_type1']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>ID Number for First Examination:</th>
                                                    <th><?php echo $data02['exam1_id']; ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="col-lg-6">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Scratch card number for second result:</th>
                                                    <th><?php echo $data02['waec_resilt2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Pin for Scratch Card Result 2:</th>
                                                    <th><?php echo $data02['pin2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Second Exam Year:</th>
                                                    <th><?php echo $data02['sec_exam_year2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Examination Body:</th>
                                                    <th><?php echo $data02['exam_body2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Other Examination Body Name:</th>
                                                    <th><?php echo $data02['other_body_name2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Examination Type:</th>
                                                    <th><?php echo $data02['exam_type2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Other Examination Type:</th>
                                                    <th><?php echo $data02['other_exm_type2']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>ID Number for Second Examination:</th>
                                                    <th><?php echo $data02['exam2_id']; ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12" style="text-align:center">
                                        <a href="https://www.waecsierra-leone.org/ResultChecker/" target="_blank">Check
                                            for Result</a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php } // end if applicantData ?>
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
                            <div class="card-body" id="applications_section">
                                <?php
                                    $sql=$conn->prepare("SELECT
                                                            tbl_admittedPRG.*,
                                                            tbl_program_type.prg_type_full_name,
                                                            tbl_faculty.fac_full_name,
                                                            tbl_department.dept_full_name,
                                                            tbl_specialization.splz_full_name,
                                                            tbl_campus.camp_full_name,
                                                            tbl_program_mode.prg_mode_full_name
                                                        FROM tbl_admittedPRG
                                                        INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                        INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                                                        INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                                                        INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                                                        INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                        INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                        WHERE tbl_admittedPRG.Stu_code = ? AND tbl_admittedPRG.sts = '2'");
                                    $sql->execute([$app_code]);
                                    while($apps=$sql->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <div class="row"
                                    style="border-radius:5px;margin-bottom:10px;<?php echo $apps['sts']==4?'border: 2px solid red':'border: 2px solid grey'; ?>">
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Campus</div>
                                            <div class="user-detail-name"><b><?php echo $apps['camp_full_name']; ?></b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job"><b>Program Type</b></div>
                                            <div class="user-detail-name">
                                                <b><?php echo $apps['prg_type_full_name']; ?></b></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Faculty</div>
                                            <div class="user-detail-name"><b><?php echo $apps['fac_full_name']; ?></b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Department</div>
                                            <div class="user-detail-name"><b><?php echo $apps['dept_full_name']; ?></b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Program</div>
                                            <div class="user-detail-name"><b><?php echo $apps['splz_full_name']; ?></b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                        <div class="article-user-details">
                                            <div class="text-job">Learning Mode</div>
                                            <div class="user-detail-name">
                                                <b><?php echo $apps['prg_mode_full_name']; ?></b></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-12"
                                        style="display:flex;flex-direction:row;justify-content:center">
                                        <?php if($apps['sts']==1){ ?>
                                        <button class="col-4 col-md-3 col-lg-3 btn btn-primary btn-sm forward"
                                            data-id="<?php echo $apps['Aprg_id']; ?>">
                                            <span id="spinneradm_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;
                                            <span id="indicatoradm_<?php echo $apps['Aprg_id']; ?>">Forward</span>
                                        </button>&nbsp;&nbsp;
                                        <button class="col-4 col-md-3 col-lg-3 btn btn-danger btn-sm com"
                                            data-id="<?php echo $apps['Aprg_id']; ?>">
                                            <span id="spinnerrej_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;
                                            <span id="indicatorrej_<?php echo $apps['Aprg_id']; ?>">Communicate</span>
                                        </button>
                                        <?php } ?>
                                    </div>
                                </div><br>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
    $checkEssay=$conn->prepare("SELECT * FROM tbl_applicant_essays WHERE stu=?");
    $checkEssay->execute([$app_code]);
    $essay=$checkEssay->rowCount();
    $data_ess=$checkEssay->fetch(PDO::FETCH_ASSOC);
    if($essay>0){
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Research proposal</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse_research" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse_research">
                            <div class="card-body">
                                <h6>Please provide a detailed research proposal for your intended study.</h6>
                                <div class="form-group">
                                    <textarea name="essay" class="essay"
                                        readonly><?php echo $data_ess['essay']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

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
                                $sql2 = $conn->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = ?");
                                $sql2->execute([$app_code]);
                                
                                while ($docs = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                    $string = $docs['upload_doc'];
                                    $parts = explode('/', $string);
                                    $after_slash = end($parts);
                                    $nameParts = explode('_', $after_slash);
                                    $before_underscore = isset($nameParts[1]) ? $nameParts[0] . "_" . $nameParts[1] : $nameParts[0];

                                    $isql2 = $conn->prepare("SELECT * FROM tbl_document_type WHERE file_name = ?");
                                    $isql2->execute([$before_underscore]);
                                    $moreInfo = $isql2->fetch(PDO::FETCH_ASSOC);

                                    if ($moreInfo) {
                                        $fileType = $moreInfo['file_type'];
                                        $docName  = $moreInfo['document_name'];
                                    } else {
                                        $fileType = 'other';
                                        $docName  = $before_underscore;
                                    }
                                ?>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <article class="article">
                                        <div class="article-header">
                                            <div class="article-image"
                                                data-background="../..<?php echo ($fileType == "image") ? $docs['upload_doc'] : "/student_docs/pdf.png"; ?>">
                                            </div>
                                            <div class="article-title">
                                                <h2><a href="../..<?php echo $docs['upload_doc']; ?>"
                                                        target="_blank"><?php echo htmlspecialchars($docName); ?></a>
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

    <!--Communicate modal-->
    <form action="" method="POST" id="student_form">
        <div class="modal fade" tabindex="-1" role="dialog" id="rejModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Communicate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ad_m_id" id="ad_m_id">
                        <input type="hidden" name="action" value="communicate">
                        <div class="form-group">
                            <label>Message</label>
                            <div class="input-group">
                                <textarea name="message" placeholder="your message goes here" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="r_appBtn"><span
                                id="spinner_modal"></span>&nbsp;<span id="indicator_modal">Send</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    // Forward application
    $(document).on('click', '.forward', function(e) {
        e.preventDefault();
        var app = $(this).data('id');
        var formData = {
            ad_m_i: app,
            action: 'forward'
        };
        swal({
            title: "Are you sure?",
            text: "This operation is not reversible!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinneradm_' + app).html("<img src='../../img/ajax_loader.gif' width='15'>")
                    .fadeIn('fast');
                $('#indicatoradm_' + app).html("forwarding...");
                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function(data) {
                        $('#spinneradm_' + app).fadeOut('fast');
                        $('#indicatoradm_' + app).html("Forward");
                        if (data.status == 200) {
                            swal("Success", data.message, "success").then(() => {
                                location.reload();
                            });
                        }
                        if (data.status == 400) {
                            swal("Error", data.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $('#spinneradm_' + app).fadeOut('fast');
                        $('#indicatoradm_' + app).html("Forward");
                        console.log(xhr.responseText);
                        swal("Error", "Something went wrong!", "error");
                    }
                });
            } else {
                swal("Operation cancelled!!");
            }
        });
    });

    // Open communicate modal
    $(document).on('click', '.com', function(e) {
        var app = $(this).data('id');
        $("#ad_m_id").val(app);
        $("#rejModal").modal('show');
    });

    // Submit communicate form
    $("#student_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        swal({
            title: "Are you sure?",
            text: "This operation is not reversible!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinner_modal').html("<img src='../../img/ajax_loader.gif' width='15'>")
                    .fadeIn('fast');
                $('#indicator_modal').html("Sending...");
                $.ajax({
                    url: "controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#spinner_modal').fadeOut('fast');
                        $('#indicator_modal').html("Send");
                        if (data.status == 200) {
                            swal("Success", data.message, "success").then(() => {
                                location.reload();
                            });
                            $("#rejModal").modal('hide');
                        }
                        if (data.status == 500) {
                            swal("Error", data.message, "error");
                        }
                    },
                    error: function(xhr) {
                        $('#spinner_modal').fadeOut('fast');
                        $('#indicator_modal').html("Send");
                        console.log(xhr.responseText);
                        swal("Error", "Something went wrong!", "error");
                    }
                });
            } else {
                swal("Operation cancelled!!");
            }
        });
    });
});
</script>