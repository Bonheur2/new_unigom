<?php
ob_start();
session_start();
require('../../fpdf17/fpdf.php');
require('../../meet/con.php');

$reg_no = isset($_GET['stu']) ? $_GET['stu'] : '';

if (empty($reg_no)) {
    die("Student ID is required.");
}

$stmt = $conn->prepare("SELECT tbl_admission.*,
    tbl_nationality.nationality as nat,
    tbl_country.cntr_name as cname,
    provinces.provincename as pname,
    districts.namedistrict as dname
    FROM tbl_admission
    LEFT JOIN tbl_nationality ON tbl_admission.nationality = tbl_nationality.nat_id
    LEFT JOIN tbl_country ON tbl_admission.country = tbl_country.cntr_id
    LEFT JOIN provinces ON tbl_admission.province_id = provinces.provincecode
    LEFT JOIN districts ON tbl_admission.district_id = districts.districtcode
    WHERE tbl_admission.reg_no = ?");
$stmt->execute([$reg_no]);
$stu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$stu) {
    die("Student not found.");
}

// Fetch university info for header/logo
$uniStmt = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
$uniStmt->execute();
$uniData = $uniStmt->fetch(PDO::FETCH_ASSOC);

$fullName = $stu['fname'] . ' ' . $stu['lname'];
$nationality = ($stu['nationality'] != 0) ? $stu['nat'] : 'Others';
$country = $stu['cname'] ?? 'N/A';
$province = ($stu['province_id'] != 0) ? $stu['pname'] : 'N/A';
$district = ($stu['district_id'] != 0) ? $stu['dname'] : 'N/A';
$street = !empty($stu['street']) ? $stu['street'] : 'N/A';
$contactAddress = $country . ', ' . $province . ', ' . $district . ', ' . $street;
$email = $stu['email'] ?? 'N/A';
$phone = $stu['phone'] ?? 'N/A';
$parentGuardian = $stu['father_names'] . ' / ' . $stu['mother_names'];
$parentPhone = $stu['parent_phone'] ?? 'N/A';
$sponsor = isset($stu['sponsor']) && !empty($stu['sponsor']) ? $stu['sponsor'] : 'N/A';
$gender = ($stu['gender'] == 'M') ? 'Male' : 'Female';
$idPassport = $stu['ID'] ?? 'N/A';

// Fetch academic information
$acadStmt = $conn->prepare("SELECT
    tbl_register_program_ug.*,
    tbl_program_type.prg_type_full_name,
    tbl_faculty.fac_full_name,
    tbl_department.dept_full_name,
    tbl_specialization.splz_full_name,
    tbl_level.level_full_name,
    tbl_acad_cycle.acad_year,
    tbl_program_mode.prg_mode_full_name
FROM tbl_register_program_ug
INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no = tbl_admission.reg_no
INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
INNER JOIN tbl_acad_cycle ON tbl_register_program_ug.acad_cycle_id = tbl_acad_cycle.acad_cycle_id
INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
WHERE tbl_register_program_ug.reg_no = ?");
$acadStmt->execute([$reg_no]);
$acadRecords = $acadStmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// ---- University Header with Logo ----
if ($uniData) {
    $pdf->Image('../..' . $uniData['logo'], 10, 10, 30);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(55, 24, 60);
    $pdf->SetX(50);
    $pdf->Cell(0, 6, $uniData['full_name'], 0, 1, 'C');
    $pdf->Ln(3);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(50);
    $pdf->Cell(0, 5, $uniData['po_box'], 0, 1, 'C');
    $pdf->SetTextColor(53, 75, 136);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(50);
    $pdf->Cell(0, 5, $uniData['website'], 0, 1, 'C');
    $pdf->Ln(5);
}

// ---- Title ----
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(0, 10, 'STUDENT INFORMATION DOCUMENT', 0, 1, 'C');
$pdf->Ln(3);

// Horizontal line
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(8);

// Student ID
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Student ID:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $reg_no, 0, 1);

// Full Name
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Full Name:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $fullName, 0, 1);

// Gender
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Gender:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $gender, 0, 1);

// Nationality
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Nationality:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $nationality, 0, 1);

// ID/Passport
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'ID / Passport:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $idPassport, 0, 1);

$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Contact Address
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Contact Address:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 10, $contactAddress, 0, 'L');

// Email
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Email:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $email, 0, 1);

// Phone
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Phone:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $phone, 0, 1);

$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Parent/Guardian
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Name of Parent/Guardian:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $parentGuardian, 0, 1);

// Parent Phone
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Parent/Guardian Phone:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $parentPhone, 0, 1);

$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Sponsor
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 10, 'Sponsor:', 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, $sponsor, 0, 1);

$pdf->Ln(5);

// ---- Academic Information Section ----
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(0, 10, 'ACADEMIC INFORMATION', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

if (!empty($acadRecords)) {
    $recNum = 1;
    foreach ($acadRecords as $acad) {
        $statusColor = ($acad['reg_active'] == 1) ? 'Active' : 'Inactive';

        // Status label
        $pdf->SetFont('Arial', 'B', 10);
        if ($acad['reg_active'] == 1) {
            $pdf->SetTextColor(0, 128, 0);
            $pdf->Cell(0, 8, 'Record #' . $recNum . ' - Active', 0, 1);
        } else {
            $pdf->SetTextColor(255, 140, 0);
            $pdf->Cell(0, 8, 'Record #' . $recNum . ' - Inactive', 0, 1);
        }
        $pdf->SetTextColor(0, 0, 0);

        // Program Type
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Program Type:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['prg_type_full_name'], 0, 'L');

        // Faculty
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Faculty:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['fac_full_name'], 0, 'L');

        // Department
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Department:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['dept_full_name'], 0, 'L');

        // Specialization
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Specialization:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['splz_full_name'], 0, 'L');

        // Level
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Level:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['level_full_name'], 0, 'L');

        // Academic Year
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Academic Year:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['acad_year'], 0, 'L');

        // Learning Mode
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 8, 'Learning Mode:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, $acad['prg_mode_full_name'], 0, 'L');

        $pdf->Ln(3);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);

        $recNum++;
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'No academic records found.', 0, 1, 'C');
}

$pdf->Ln(10);

// Footer note
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'R');

// Output - use 'I' for inline display (same as financial report)
ob_end_clean();
$dname = 'Student_Info_' . str_replace('/', '_', $reg_no);
$pdf->Output($dname . '.pdf', 'I');
exit;
