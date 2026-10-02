<?php
ob_start();
session_start();
require('../../fpdf17/fpdf.php');
require('../../meet/con.php');

$splz_id = isset($_GET['splz_id']) ? $_GET['splz_id'] : '';
$level_id = isset($_GET['level_id']) ? $_GET['level_id'] : '';
$acad_cycle_id = isset($_GET['acad_cycle_id']) ? $_GET['acad_cycle_id'] : '';

if (empty($splz_id) || empty($level_id)) {
    die("Specialization and Level are required.");
}

// Fetch university info
$uniStmt = $conn->prepare("SELECT * FROM tbl_university ORDER BY id ASC LIMIT 1");
$uniStmt->execute();
$uniData = $uniStmt->fetch(PDO::FETCH_ASSOC);

// Fetch specialization name
$specStmt = $conn->prepare("SELECT s.splz_full_name, d.dept_full_name, f.fac_full_name, pt.prg_type_full_name
    FROM tbl_specialization s
    INNER JOIN tbl_department d ON s.dept_id = d.dept_id
    INNER JOIN tbl_faculty f ON s.fac_id = f.fac_id
    INNER JOIN tbl_program_type pt ON s.prg_type = pt.prg_type_id
    WHERE s.splz_id = ?");
$specStmt->execute([$splz_id]);
$specData = $specStmt->fetch(PDO::FETCH_ASSOC);

// Fetch level name
$lvlStmt = $conn->prepare("SELECT level_full_name FROM tbl_level WHERE level_id = ?");
$lvlStmt->execute([$level_id]);
$lvlData = $lvlStmt->fetch(PDO::FETCH_ASSOC);

// Fetch academic year name
$acadYearName = 'All';
if (!empty($acad_cycle_id)) {
    $acStmt = $conn->prepare("SELECT acad_year FROM tbl_acad_cycle WHERE acad_cycle_id = ?");
    $acStmt->execute([$acad_cycle_id]);
    $acData = $acStmt->fetch(PDO::FETCH_ASSOC);
    $acadYearName = $acData['acad_year'] ?? 'All';
}

// Fetch students
$sql = "SELECT 
    tbl_register_program_ug.reg_no,
    tbl_register_program_ug.reg_active,
    tbl_admission.fname,
    tbl_admission.lname,
    tbl_admission.gender,
    tbl_specialization.splz_full_name,
    tbl_level.level_full_name
FROM tbl_register_program_ug
INNER JOIN tbl_admission ON tbl_register_program_ug.reg_no = tbl_admission.reg_no
INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
WHERE tbl_register_program_ug.splz_id = ? AND tbl_register_program_ug.level_id = ? AND tbl_register_program_ug.reg_active = 1";

$params = [$splz_id, $level_id];

if (!empty($acad_cycle_id)) {
    $sql .= " AND tbl_register_program_ug.acad_cycle_id = ?";
    $params[] = $acad_cycle_id;
}

$sql .= " ORDER BY tbl_admission.fname ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// ---- University Header with Logo ----
if ($uniData) {
    $logoPath = '../..' . $uniData['logo'];
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 10, 10, 30);
    }
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
$pdf->Cell(0, 10, 'STUDENT LIST', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// ---- Filter Info ----
if ($specData) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, 'Program Type:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, $specData['prg_type_full_name'], 0, 1);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, 'Faculty:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, $specData['fac_full_name'], 0, 1);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, 'Department:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, $specData['dept_full_name'], 0, 1);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, 'Specialization:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, $specData['splz_full_name'], 0, 1);
}

if ($lvlData) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 7, 'Level:', 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, $lvlData['level_full_name'], 0, 1);
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 7, 'Academic Year:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, $acadYearName, 0, 1);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 7, 'Total Students:', 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, count($students), 0, 1);

$pdf->Ln(3);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// ---- Student Table ----
if (!empty($students)) {
    // Table header
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(55, 24, 60);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(12, 8, '#', 1, 0, 'C', true);
    $pdf->Cell(45, 8, 'Student ID', 1, 0, 'C', true);
    $pdf->Cell(108, 8, 'Full Name', 1, 0, 'C', true);
    $pdf->Cell(25, 8, 'Gender', 1, 1, 'C', true);

    // Table rows
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $fill = false;
    $i = 1;
    foreach ($students as $stu) {
        $pdf->SetFillColor(240, 240, 240);
        $genderText = ($stu['gender'] == 'M') ? 'Male' : 'Female';

        $pdf->Cell(12, 7, $i, 1, 0, 'C', $fill);
        $pdf->Cell(45, 7, $stu['reg_no'], 1, 0, 'L', $fill);
        $pdf->Cell(108, 7, $stu['fname'] . ' ' . $stu['lname'], 1, 0, 'L', $fill);
        $pdf->Cell(25, 7, $genderText, 1, 1, 'C', $fill);

        $fill = !$fill;
        $i++;
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'No students found for the selected criteria.', 0, 1, 'C');
}

$pdf->Ln(10);

// Footer note
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'R');

// Output
ob_end_clean();
$dname = 'Student_List_' . $splz_id . '_' . $level_id;
$pdf->Output($dname . '.pdf', 'I');
exit;
