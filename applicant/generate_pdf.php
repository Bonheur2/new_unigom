<?php
// Include FPDF library
require_once('../fpdf/fpdf.php');

// Include database connection
include ('../meet/con.php');
// Get the applicant code from URL
$code = $_GET['code'] ?? '';

if (empty($code)) {
    die('Invalid application code');
}

// Database queries
try {
    // First table query - applicant information
    $stmt1 = $conn->prepare("SELECT `applicant_id`, `code`, `fname`, `mname`, `lname`, `email`, `prevname`, `ID`, `nationality`, `phone`, `gender`, `dob`, `marital_status`, `father_names`, `mother_names`, `parent_phone`, `ref_phone`, `country`, `province_id`, `district_id`, `sector`, `cell_id`, `village_id`, `kin_name`, `kin_relation`, `kin_address`, `kin_email`, `kin_tel`, `pay_slip`, `status`, `createdAt`, `submitted`, `street`, `app_type`, `app_category`, `blood_group`, `disability`, `disability_detail`, `payt_date` FROM `tbl_applicants` WHERE `code` = ?");
    $stmt1->execute([$code]);
    $applicant = $stmt1->fetch(PDO::FETCH_ASSOC);

    if (!$applicant) {
        die('Applicant not found');
    }

    // Second table query - education information
    $stmt2 = $conn->prepare("SELECT `id`, `stu`, `school`, `year_from`, `year_to`, `award`, `certificate`, `waec_result1`, `pin1`, `waec_resilt2`, `pin2`, `first_exam_year`, `exam_body1`, `other_body_name1`, `exam_type1`, `other_exm_type1`, `exam1_id`, `sec_exam_year2`, `exam_body2`, `other_body_name2`, `exam_type2`, `other_exm_type2`, `exam2_id` FROM `tbl_applicant_education` WHERE `stu` = ?");
    $stmt2->execute([$applicant['applicant_id']]);
    $education = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Third query - selected programs with all related data
    $stmt3 = $conn->prepare("SELECT
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
                                                                left JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                                                                WHERE
                                                                     tbl_admittedPRG.Stu_code = ? AND tbl_admittedPRG.sts !=0");
    $stmt3->execute([$code]);
    $programs = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // Get related data for foreign keys
    $nationality_query = $conn->prepare("SELECT nationality FROM tbl_nationality WHERE nat_id = ?");
    $nationality_query->execute([$applicant['nationality']]);
    $nationality = $nationality_query->fetch(PDO::FETCH_ASSOC);

    $country_query = $conn->prepare("SELECT cntr_name FROM tbl_country WHERE cntr_id = ?");
    $country_query->execute([$applicant['country']]);
    $country = $country_query->fetch(PDO::FETCH_ASSOC);

    $province_query = $conn->prepare("SELECT provincename FROM provinces WHERE provincecode = ?");
    $province_query->execute([$applicant['province_id']]);
    $province = $province_query->fetch(PDO::FETCH_ASSOC);

    $district_query = $conn->prepare("SELECT namedistrict FROM districts WHERE districtcode = ?");
    $district_query->execute([$applicant['district_id']]);
    $district = $district_query->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die('Database error: ' . $e->getMessage());
}

// Create PDF
class ApplicationPDF extends FPDF
{
    function Header()
{
    // Logo
    if (file_exists('../img/logo/NJALA.png')) {
        $this->Image('../img/logo/NJALA.png', 10, 6, 20); // x=10, y=6, width=20mm
    }

    // Set font for university name
    $this->SetFont('Arial', 'B', 16);
    $this->Cell(0, 10, 'NJALA UNIVERSITY', 0, 1, 'C');

    // Set font for report title
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(0, 10, 'APPLICATION OVERVIEW', 0, 1, 'C');

    // Line break
    $this->Ln(5);
}

    function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);

    // Arial italic 8
    $this->SetFont('Arial', 'I', 8);

    // Footer text
    $footerText = 'Page ' . $this->PageNo() . 
                  ' - Generated on ' . date('Y-m-d H:i:s') . 
                  ' | Designed by ITEC LTD';

    // Centered
    $this->Cell(0, 10, $footerText, 0, 0, 'C');
}

    function SectionHeader($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(0, 8, $title, 1, 1, 'L', true);
        $this->Ln(2);
    }

    function AddRow($label, $value)
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 6, $label . ':', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, $value, 0, 1, 'L');
    }
}

$pdf = new ApplicationPDF();
$pdf->AddPage();

// Application Code
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(0, 10, 'Application Code: ' . $code, 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(5);

// 1. Personal Information
$pdf->SectionHeader('PERSONAL INFORMATION');
$pdf->AddRow('First Name', $applicant['fname'] ?? '');
$pdf->AddRow('Middle Name', $applicant['mname'] ?? '');
$pdf->AddRow('Last Name', $applicant['lname'] ?? '');
$pdf->AddRow('Previous Name', $applicant['prevname'] ?? '');
$pdf->AddRow('Email', $applicant['email'] ?? '');
$pdf->AddRow('ID/Passport', $applicant['ID'] ?? '');
$pdf->AddRow('Nationality', $nationality['nationality'] ?? 'N/A');
$pdf->AddRow('Phone', $applicant['phone'] ?? '');
$pdf->AddRow('Gender', $applicant['gender'] == 'M' ? 'Male' : 'Female');
$pdf->AddRow('Date of Birth', $applicant['dob'] ?? '');
$pdf->AddRow('Marital Status', $applicant['marital_status'] ?? '');
$pdf->Ln(5);

// 2. Current Address
$pdf->SectionHeader('CURRENT ADDRESS');
$pdf->AddRow('Country', $country['cntr_name'] ?? 'N/A');
$pdf->AddRow('Province', $province['provincename'] ?? 'N/A');
$pdf->AddRow('District', $district['namedistrict'] ?? 'N/A');
$pdf->AddRow('Street', $applicant['street'] ?? '');
$pdf->Ln(5);

// 3. Contact Information
$pdf->SectionHeader('CONTACT INFORMATION');
$pdf->AddRow('Email', $applicant['email'] ?? '');
$pdf->AddRow('Phone', $applicant['phone'] ?? '');
$pdf->AddRow('Parent Phone', $applicant['parent_phone'] ?? '');
$pdf->AddRow('Reference Phone', $applicant['ref_phone'] ?? '');
$pdf->Ln(5);

// 4. Applicant Type
$pdf->SectionHeader('APPLICANT TYPE');
$pdf->AddRow('Application Type', $applicant['app_type'] ?? '');
$pdf->AddRow('Application Category', $applicant['app_category'] ?? '');
$pdf->Ln(5);

// 5. Medical & Disability
$pdf->SectionHeader('MEDICAL & DISABILITY');
$pdf->AddRow('Blood Group', $applicant['blood_group'] ?? '');
$pdf->AddRow('Disability', $applicant['disability'] ?? 'No');
$pdf->AddRow('Disability Details', $applicant['disability_detail'] ?? '');
$pdf->Ln(5);

// 6. Next of Kin/Guardian
$pdf->SectionHeader('NEXT OF KIN / GUARDIAN');
$pdf->AddRow('Name', $applicant['kin_name'] ?? '');
$pdf->AddRow('Relation', $applicant['kin_relation'] ?? '');
$pdf->AddRow('Address', $applicant['kin_address'] ?? '');
$pdf->AddRow('Email', $applicant['kin_email'] ?? '');
$pdf->AddRow('Telephone', $applicant['kin_tel'] ?? '');
$pdf->Ln(5);

// 7. Matriculation Details
$pdf->SectionHeader('MATRICULATION DETAILS');
$pdf->AddRow('Created At', $applicant['createdAt'] ?? '');
$pdf->AddRow('Submitted', $applicant['submitted'] == 1 ? 'Yes' : 'No');
$pdf->Ln(5);

// 8. Parent/Guardian Details
$pdf->SectionHeader('PARENT/GUARDIAN DETAILS');
$pdf->AddRow('Father\'s Name', $applicant['father_names'] ?? '');
$pdf->AddRow('Mother\'s Name', $applicant['mother_names'] ?? '');
$pdf->AddRow('Parent Phone', $applicant['parent_phone'] ?? '');
$pdf->Ln(5);

// 9. Secondary Schools Attended
if (!empty($education)) {
    $pdf->SectionHeader('SECONDARY SCHOOLS ATTENDED');
    foreach ($education as $edu) {
        $pdf->AddRow('School', $edu['school'] ?? '');
        $pdf->AddRow('Year From', $edu['year_from'] ?? '');
        $pdf->AddRow('Year To', $edu['year_to'] ?? '');
        $pdf->AddRow('Award/Certificate', $edu['award'] ?? '');
        $pdf->Ln(2);
    }
    $pdf->Ln(3);
}

// 10. WAEC Scratch Card Information
if (!empty($education)) {
    $pdf->SectionHeader('WAEC SCRATCH CARD INFORMATION');
    foreach ($education as $edu) {
        if (!empty($edu['waec_result1']) || !empty($edu['pin1'])) {
            $pdf->AddRow('WAEC Result 1', $edu['waec_result1'] ?? '');
            $pdf->AddRow('PIN 1', $edu['pin1'] ?? '');
        }
        if (!empty($edu['waec_resilt2']) || !empty($edu['pin2'])) {
            $pdf->AddRow('WAEC Result 2', $edu['waec_resilt2'] ?? '');
            $pdf->AddRow('PIN 2', $edu['pin2'] ?? '');
        }
        $pdf->Ln(2);
    }
    $pdf->Ln(3);
}

// 11. Language Proficiency (placeholder - add if data exists)
$pdf->SectionHeader('LANGUAGE PROFICIENCY');
$pdf->AddRow('English', 'Proficient'); // Placeholder data
$pdf->Ln(5);

// 12. Sponsorship Details
$pdf->SectionHeader('SPONSORSHIP DETAILS');
$pdf->AddRow('Payment Slip', $applicant['pay_slip'] ?? '');
$pdf->AddRow('Payment Date', $applicant['payt_date'] ?? '');
$pdf->Ln(5);

// 13. Programme Sought and Interview Location
$pdf->SectionHeader('PROGRAMME SOUGHT AND INTERVIEW LOCATION');

if (!empty($programs)) {
    $program_count = 1;
    foreach ($programs as $prog) {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, 'Programme ' . $program_count . ':', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        
        // Campus
        $pdf->AddRow('  Campus', $prog['camp_full_name'] ?? 'N/A');
        
        // Program Type
        $pdf->AddRow('  Program Type', $prog['prg_type_full_name'] ?? 'N/A');
        
        // Faculty
        $pdf->AddRow('  Faculty/School', $prog['fac_full_name'] ?? 'N/A');
        
        // Department
        $pdf->AddRow('  Department', $prog['dept_full_name'] ?? 'N/A');
        
        // Specialization
        $pdf->AddRow('  Specialization', $prog['splz_full_name'] ?? 'N/A');
        
        // Level
        // $pdf->AddRow('  Level', $prog['level_full_name'] ?? 'N/A');
        
        // Program Mode
        $pdf->AddRow('  Learning Mode', $prog['prg_mode_full_name'] ?? 'N/A');
        
        
        
        // Student Decision
        if (!empty($prog['student_decision'])) {
            $pdf->AddRow('  Your Decision', ucfirst($prog['student_decision']));
        }
        
        // Rejection Comment
        if (!empty($prog['rej_comment'])) {
            $pdf->AddRow('  Comments', $prog['rej_comment']);
        }
        
        $pdf->Ln(3);
        $program_count++;
    }
} else {
    $pdf->AddRow('Programme', 'No programs selected yet');
}

$pdf->AddRow('Interview Location', 'To be announced');

// Output the PDF
$pdf->Output('D', 'Application_Overview_' . $code . '.pdf');
?>
