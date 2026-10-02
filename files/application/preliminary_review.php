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
    textarea:focus{
        border: 1px solid grey;
        box-shadow: none;
    }
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log');

?>
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
                                                 WHERE tbl_applicants.code='".$_GET['app']."'");
                    $stmt->execute();
                    $applicantData=$stmt->fetch();
                ?>
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
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
                                            <th scope="col"><?php echo $applicantData['gender']=="M"?"Male":"Female"; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Marital status:</th>
                                            <th scope="col"><?php echo $applicantData['marital_status']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nationality:</th>
                                            <th scope="col"><?php echo $applicantData['nationality']!=0?$applicantData['nat']:'Others'; ?></th>
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
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
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
                                            <th scope="col"><?php echo $applicantData['province_id']!=0?$applicantData['pname']:'N/A'; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">District:</th>
                                            <th scope="col"><?php echo $applicantData['district_id']!=0?$applicantData['dname']:'N/A'; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Street:</th>
                                            <th scope="col"><?php echo $applicantData['street']!=0?$applicantData['street']:'N/A'; ?></th>
                                        </tr>
                                        <!--<tr>-->
                                        <!--    <th scope="col">Cell:</th>-->
                                        <!--    <th scope="col"><?php echo $applicantData['cell_id']!=0?$applicantData['cellname']:'N/A'; ?></th>-->
                                        <!--</tr>-->
                                        <!--<tr>-->
                                        <!--    <th scope="col">Village:</th>-->
                                        <!--    <th scope="col"><?php echo $applicantData['village_id']!=0?$applicantData['vname']:'N/A'; ?></th>-->
                                        <!--</tr>-->
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
                                <a data-collapse="#mycard-collapse31" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
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
                    $stmt=$conn->prepare("SELECT c.*, cnt.cntr_name FROM tbl_applicant_church c LEFT JOIN tbl_country cnt ON c.country = cnt.cntr_id WHERE c.stu='".$_GET['app']."'");
                    $stmt->execute();
                    $applicantchurch=$stmt->fetch();
                ?>
                
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Religious Information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse32" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse32">
                            <div class="card-body">
                                <div class="">
                                    <table class="table table-sm">
                                    <thead>
                                         <tr>
                                            <th scope="col">Religion:</th>
                                            <th scope="col"><?php echo $applicantchurch['church']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Country:</th>
                                            <th scope="col"><?php echo $applicantchurch['cntr_name']; ?></th>
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
                                <a data-collapse="#mycard-collapse33" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
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
                             
                                    <?php
                                    $stst02=$conn->prepare("SELECT * FROM tbl_applicant_education WHERE stu= '".$_GET['app']."' ");
                                    $stst02->execute();
                                    $data02=$stst02->fetch();
                                   if(!empty($data02['waec_result1'])){
                                    ?>
                                    
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                          <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Scratch card number for first result:</th>
                                            <th scope="col"><?php echo $data02['waec_result1']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Pin for scratch card result 1:</th>
                                            <th scope="col"><?php echo $data02['pin1']; ?></th>
                                        </tr>
                                       
                                        <tr>
                                            <th scope="col">First Exam Year:</th>
                                            <th scope="col"><?php echo $data02['first_exam_year']; ?></th>
                                        </tr>
                                         <tr>
                                            <th scope="col">Examination Body:</th>
                                            <th scope="col"><?php echo $data02['exam_body1']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Other examination body name:</th>
                                            <th scope="col"><?php echo $data02['other_body_name1']; ?></th>
                                        </tr>
                                        <tr>
                                           <th scope="col">Examination Type:</th>
                                           <th scope="col"><?php echo $data02['exam_type1']; ?></th> 
                                        </tr>
                                        <tr>
                                           <th scope="col">Other Examination Type:</th>
                                           <th scope="col"><?php echo $data02['other_exm_type1']; ?></th> 
                                        </tr>
                                        <tr>
                                           <th scope="col">ID Number for First Examination:</th>
                                           <th scope="col"><?php echo $data02['exam1_id']; ?></th> 
                                        </tr>
                                    </thead>
                                    </table>    
                                   </div>
                                    <div class="col-lg-6">
                                              <table class="table table-sm">
                                    <thead>
                                         <tr>
                                            <th scope="col">Scratch card number for second result:</th>
                                            <th scope="col"><?php echo $data02['waec_resilt2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Pin for Scratch Card Result 2:</th>
                                            <th scope="col"><?php echo $data02['pin2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Second Exam Year:</th>
                                            <th scope="col"><?php echo $data02['sec_exam_year2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Examination Body:</th>
                                            <th scope="col"><?php echo $data02['exam_body2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Other Examination Body Name:</th>
                                            <th scope="col"><?php echo $data02['other_body_name2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Examination Type:</th>
                                            <th scope="col"><?php echo $data02['exam_type2']; ?></th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Other Examination Type:</th>
                                            <th scope="col"><?php echo $data02['other_exm_type2']; ?></th>
                                        </tr>
                                         <tr>
                                            <th scope="col">ID Number for Second Examination:</th>
                                            <th scope="col"><?php echo $data02['exam2_id']; ?></th>
                                        </tr>
                                    </thead>
                                    </table>
                                        </div>
                                    </div>
                                 <form action="" method="POST" class="row">
                                    <div class="col-lg-12" style="text-align:center">
                                         <a href="https://www.waecsierra-leone.org/ResultChecker/" target="_blank">Check for Result</a>
                                    </div>
                                    <br>
                                 </form>
                                 
                               </div>
                                  <?php } ?>
                                   
                                    
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
                            <h4>Submitted applications </h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-app" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse-app">
                            <div class="card-body" id="applications">
                                <?php
                                ini_set('display_errors', 1);
                                ini_set('display_startup_errors', 1);
                                error_reporting(E_ALL);
                                    $sql=$conn->prepare("SELECT
                                                            tbl_admittedPRG.*,
                                                            tbl_program_type.prg_type_full_name,
                                                            tbl_specialization.splz_full_name,
                                                            tbl_campus.camp_full_name,
                                                            tbl_program_mode.prg_mode_full_name,
                                                        FROM
                                                            tbl_admittedPRG
                                                        INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                                                        INNER JOIN tbl_specialization ON tbl_admittedPRG.dept_id = tbl_specialization.splz_id
                                                        INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                                                        INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                                                        WHERE
                                                             tbl_admittedPRG.Stu_code = '".$_GET['app']."' AND tbl_admittedPRG.sts='2'
                                                            ");
                                    $sql->execute();
                                    $i=1;
                                    while($apps=$sql->fetch()){
                                ?>
                                <div class="row" style="border-radius:5px;margin-bottom:10px;<?php echo $apps['sts']==4?'border: 2px solid red':'border: 2px solid grey'; ?>">
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                            <div class="article-user-details">
                                                <div class="text-job">Campus</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $apps['camp_full_name']; ?></b></a></div>
                                            </div>
                                    </div>
                                    <div class="form-group col-6 col-md-3 col-lg-2">
                                            <div class="article-user-details">
                                                <div class="text-job"><b>Program Type</b></div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $apps['prg_type_full_name']; ?></b></a></div>
                                            </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-4">
                                            <div class="article-user-details">
                                                <div class="text-job">Program</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $apps['splz_full_name']; ?></b></a></div>
                                            </div>
                                    </div>
                                    <div class="form-group col-6 col-md-2 col-lg-2">
                                            <div class="article-user-details">
                                                <div class="text-job">Learning Mode</div>
                                                <div class="user-detail-name"><a href="#"><b><?php echo $apps['sts'] . ": " . $apps['prg_mode_full_name']; ?></b></a></div>
                                            </div>
                                    </div>
                                    <div class="form-group col-12 col-md-12 col-lg-12" style="display:flex;flex-direction:row;justify-content:center">
                                        <?php if($apps['sts']==1){ ?>
                                        <button class=" col-4 col-md-3 col-lg-3 btn btn-primary btn-sm forward" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinneradm_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatoradm_<?php echo $apps['Aprg_id']; ?>">Forward</span>&nbsp;</button>&nbsp;&nbsp;
                                        <button class=" col-4 col-md-3 col-lg-3 btn btn-danger btn-sm com" data-id="<?php echo $apps['Aprg_id']; ?>"><span id="spinnerrej_<?php echo $apps['Aprg_id']; ?>"></span>&nbsp;<span id="indicatorrej_<?php echo $apps['Aprg_id']; ?>">Communicate</span>&nbsp;</button>
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
                                <a data-collapse="#mycard-collapse_research" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse_research">
                            <div class="card-body">
                                <h6>Please provide a detailed research proposal for your intended study. Your proposal should include the title, introduction, objectives, literature review, methodology, expected outcomes, timeline, and references. Ensure that your proposal is well-organized and thoroughly addresses each section.</h6>
                                <div class="form-group">
                                    <?php echo $data_ess['essay'] ?>
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
                            <a data-collapse="#mycard-collapse-doc" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="collapse hide" id="mycard-collapse-doc">
                        <div class="card-body row">

                            <?php
                            // fetch all uploaded docs for applicant
                            $sql2 = $conn->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = ?");
                            $sql2->execute([$_GET['app']]);
                            
                            while ($docs = $sql2->fetch(PDO::FETCH_ASSOC)) {
                                $string = $docs['upload_doc'];

                                // get filename after last slash
                                $parts = explode('/', $string);
                                $after_slash = end($parts);

                                // split by underscore (e.g., Passport_photo_25NJ31790.jpg)
                                $nameParts = explode('_', $after_slash);

                                // take first 2 parts to match DB file_name (e.g., Passport_photo)
                                $before_underscore = isset($nameParts[1]) ? $nameParts[0] . "_" . $nameParts[1] : $nameParts[0];

                                // query document type info
                                $isql2 = $conn->prepare("SELECT * FROM tbl_document_type WHERE file_name = ?");
                                $isql2->execute([$before_underscore]);
                                $moreInfo = $isql2->fetch(PDO::FETCH_ASSOC);

                                // fallback values if no DB record found
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
                                            <div class="article-image" data-background="../..<?php echo ($fileType == "image") ? $docs['upload_doc'] : "/student_docs/pdf.png"; ?>">
                                            </div>
                                            <div class="article-title">
                                                <h2>
                                                    <a href="../..<?php echo $docs['upload_doc']; ?>" target="_blank">
                                                        <?php echo htmlspecialchars($docName); ?>
                                                    </a>
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

                    
    <!--Applicant modal-->
    <form action="student_form" method="POST" id="student_form">
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
                        <button type="submit" class="btn btn-primary" id="r_appBtn"><span id="spinner2"></span>&nbsp;<span id="indicator2">Reject</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end update modal-->
</div>
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    //forward application
    $(".forward").click(function(e){
            e.preventDefault();
        var app=$(this).data('id');
        var formData = {
            ad_m_i:app,
            action:'forward'
        }
        swal({
            title: "Are you sure?",
            text: "This operation is not reversible!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('#spinneradm_'+app).html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicatoradm_'+app).html("forwarding...");
                    $.ajax({
                        url: "/files/admission/admission_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        success: function(data){
                            $('#spinneradm_'+app).fadeOut('fast');
                            $('#indicatoradm_'+app).html("forward");
                            if(data.status==200){
                                pop_up_success(data.message);
                                $('#applications').load(location.href + " #applications");
                            }
                            if(data.status==400){
                                pop_wrong(data.message);
                            }
                        },error: function(){
                            $('#spinneradm_'+app).fadeOut('fast');
                            $('#indicatoradm_'+app).html("forward");
                            pop_wrong("Something went wrong!");
                        }
                     });
            }
           else {
                swal("Operation cancelled!!");
            }
        });
    });


    $(".com").click(function(e){
        var app=$(this).data('id');
        $("#ad_m_id").val(app);
        $("#rejModal").modal('show');
    });

    //reject application
    $("#student_form").submit(function(e){
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
                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator2').html("Communicating...");
                    $.ajax({
                        url: "/files/admission/admission_controller.php",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        processData:false,
                        contentType:false,
                        success: function(data){
                            $('#spinner2').fadeOut('fast');
                            $('#indicator2').html("Communicate");
                            if(data.status==200){
                                pop_up_success(data.message);
                                $('#applications').load(location.href + " #applications");
                            }
                            if(data.status==500){
                                pop_wrong(data.message);
                            }
                        },error: function(){
                            $('#spinner2').fadeOut('fast');
                            $('#indicator2').html("Communicate");
                            pop_wrong("Something went wrong!");
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
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>