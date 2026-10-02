<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <?php
            $applicant_id = $_GET['app'] ?? 0;

            $stmt = $conn->prepare("SELECT tbl_applicants.*,
                                            tbl_nationality.nationality AS nat_name,
                                            tbl_country.cntr_name AS country_name,
                                            tbl_campus.camp_full_name,
                                            tbl_program_type.prg_type_full_name,
                                            d1.dept_full_name AS dept_choice_1_name,
                                            d2.dept_full_name AS dept_choice_2_name
                                        FROM tbl_applicants
                                        LEFT JOIN tbl_nationality ON tbl_applicants.nationality_id = tbl_nationality.nat_id
                                        LEFT JOIN tbl_country ON tbl_applicants.country_id = tbl_country.cntr_id
                                        LEFT JOIN tbl_campus ON tbl_applicants.camp_id = tbl_campus.camp_id
                                        LEFT JOIN tbl_program_type ON tbl_applicants.prg_type_id = tbl_program_type.prg_type_id
                                        LEFT JOIN tbl_department d1 ON tbl_applicants.dept_choice_1 = d1.dept_id
                                        LEFT JOIN tbl_department d2 ON tbl_applicants.dept_choice_2 = d2.dept_id
                                        WHERE tbl_applicants.applicant_id = :id");
            $stmt->execute([':id' => $applicant_id]);
            $applicantData = $stmt->fetch(PDO::FETCH_ASSOC);

            $status_badge = [
                'pending' => 'badge-warning',
                'verified' => 'badge-info',
                'accepted' => 'badge-success',
                'rejected' => 'badge-danger'
            ];
        ?>
        <div class="section-header">
            <h3><?php echo $applicantData ? htmlspecialchars($applicantData['application_code']) : 'Application not found'; ?></h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="applications">Applicants</a></div>
                <div class="breadcrumb-item"><a href="#">Review</a></div>
            </div>
        </div>
    </section>

    <?php if(!$applicantData): ?>
    <section class="section">
        <div class="section-body">
            <div class="alert alert-danger">Application not found.</div>
        </div>
    </section>
    <?php else: ?>

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($applicantData['fname'].' '.$applicantData['mname'].' '.$applicantData['lname']); ?></h4>
                                <span class="text-muted"><?php echo htmlspecialchars($applicantData['email']); ?> &nbsp;|&nbsp; <?php echo htmlspecialchars($applicantData['phone']); ?></span>
                            </div>
                            <span class="badge <?php echo $status_badge[$applicantData['status']] ?? 'badge-secondary'; ?> p-2" style="font-size:14px;"><?php echo ucfirst($applicantData['status']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="profile">

                <!-- Personal information -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Personal Information</h4>
                            <div class="card-header-action">
                                <a data-collapse="#card-personal" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="card-personal">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th scope="row">First name</th><td><?php echo htmlspecialchars($applicantData['fname']); ?></td></tr>
                                        <tr><th scope="row">Middle name</th><td><?php echo htmlspecialchars($applicantData['mname']); ?></td></tr>
                                        <tr><th scope="row">Last name</th><td><?php echo htmlspecialchars($applicantData['lname']); ?></td></tr>
                                        <tr><th scope="row">Gender</th><td><?php echo $applicantData['gender']=='M' ? 'Male' : 'Female'; ?></td></tr>
                                        <tr><th scope="row">Date of birth</th><td><?php echo htmlspecialchars($applicantData['dob']); ?></td></tr>
                                        <tr><th scope="row">Place of birth</th><td><?php echo htmlspecialchars($applicantData['place_of_birth']); ?></td></tr>
                                        <tr><th scope="row">National ID / Passport</th><td><?php echo htmlspecialchars($applicantData['nid']); ?></td></tr>
                                        <tr><th scope="row">Blood type</th><td><?php echo htmlspecialchars($applicantData['blood_type']); ?></td></tr>
                                        <tr><th scope="row">Marital status</th><td><?php echo htmlspecialchars($applicantData['marital_status']); ?></td></tr>
                                        <tr><th scope="row">Religious affiliation</th><td><?php echo htmlspecialchars($applicantData['religious_affiliation']); ?></td></tr>
                                        <tr><th scope="row">Nationality</th><td><?php echo htmlspecialchars($applicantData['nat_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">Father's name</th><td><?php echo htmlspecialchars($applicantData['father_name']); ?></td></tr>
                                        <tr><th scope="row">Mother's name</th><td><?php echo htmlspecialchars($applicantData['mother_name']); ?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Origin & Contact -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Origin &amp; Contact</h4>
                            <div class="card-header-action">
                                <a data-collapse="#card-contact" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="card-contact">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th scope="row">Country of residence</th><td><?php echo htmlspecialchars($applicantData['country_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">Parents' province of origin</th><td><?php echo htmlspecialchars($applicantData['parents_province_origin']); ?></td></tr>
                                        <tr><th scope="row">Territory of origin</th><td><?php echo htmlspecialchars($applicantData['territory_of_origin']); ?></td></tr>
                                        <tr><th scope="row">Candidate's address</th><td><?php echo htmlspecialchars($applicantData['candidate_address']); ?></td></tr>
                                        <tr><th scope="row">Email</th><td><?php echo htmlspecialchars($applicantData['email']); ?></td></tr>
                                        <tr><th scope="row">Phone</th><td><?php echo htmlspecialchars($applicantData['phone']); ?></td></tr>
                                        <tr><th scope="row">Father's / parent phone</th><td><?php echo htmlspecialchars($applicantData['parent_phone']); ?></td></tr>
                                        <tr><th scope="row">Mother's phone</th><td><?php echo htmlspecialchars($applicantData['ref_phone']); ?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Education -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Secondary Education</h4>
                            <div class="card-header-action">
                                <a data-collapse="#card-secondary" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="card-secondary">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th scope="row">School name</th><td><?php echo htmlspecialchars($applicantData['secondary_school_name']); ?></td></tr>
                                        <tr><th scope="row">School province</th><td><?php echo htmlspecialchars($applicantData['secondary_school_province']); ?></td></tr>
                                        <tr><th scope="row">School territory</th><td><?php echo htmlspecialchars($applicantData['secondary_school_territory']); ?></td></tr>
                                        <tr><th scope="row">Humanities section</th><td><?php echo htmlspecialchars($applicantData['humanities_section']); ?></td></tr>
                                        <tr><th scope="row">School status</th><td><?php echo htmlspecialchars($applicantData['secondary_school_status']); ?></td></tr>
                                        <tr><th scope="row">Diploma year</th><td><?php echo htmlspecialchars($applicantData['diploma_year']); ?></td></tr>
                                        <tr><th scope="row">Diploma percentage</th><td><?php echo htmlspecialchars($applicantData['diploma_percentage']); ?></td></tr>
                                        <tr><th scope="row">State diploma number</th><td><?php echo htmlspecialchars($applicantData['state_diploma_number']); ?></td></tr>
                                        <tr><th scope="row">Professional activities</th><td><?php echo nl2br(htmlspecialchars($applicantData['professional_activities'])); ?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Choice -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Academic Choice</h4>
                            <div class="card-header-action">
                                <a data-collapse="#card-academic" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="card-academic">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th scope="row">Campus</th><td><?php echo htmlspecialchars($applicantData['camp_full_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">Programme Type</th><td><?php echo htmlspecialchars($applicantData['prg_type_full_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">1st choice department</th><td><?php echo htmlspecialchars($applicantData['dept_choice_1_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">2nd choice department</th><td><?php echo htmlspecialchars($applicantData['dept_choice_2_name'] ?? 'N/A'); ?></td></tr>
                                        <tr><th scope="row">Application code</th><td><?php echo htmlspecialchars($applicantData['application_code']); ?></td></tr>
                                        <tr><th scope="row">Submitted on</th><td><?php echo date('Y-m-d H:i', strtotime($applicantData['created_at'])); ?></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Documents -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Uploaded Documents</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Uploaded On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $docSql = $conn->prepare("SELECT tbl_applicant_documents.file_path, tbl_applicant_documents.uploaded_at,
                                                                             tbl_document_type.document_name
                                                                        FROM tbl_applicant_documents
                                                                        LEFT JOIN tbl_document_type ON tbl_document_type.doc_id = tbl_applicant_documents.doc_id
                                                                        WHERE tbl_applicant_documents.applicant_id = :id
                                                                        ORDER BY tbl_document_type.document_name ASC");
                                            $docSql->execute([':id' => $applicant_id]);
                                            $docs = $docSql->fetchAll(PDO::FETCH_ASSOC);
                                        ?>
                                        <?php if(empty($docs)): ?>
                                        <tr><td colspan="3" class="text-center text-muted">No documents uploaded.</td></tr>
                                        <?php else: ?>
                                        <?php foreach($docs as $doc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($doc['document_name'] ?? 'Document'); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($doc['uploaded_at'])); ?></td>
                                            <td><a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
