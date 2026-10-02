<?php
ob_start();
session_start();
require('../../fpdf17/fpdf.php');
require('../../meet/con.php'); // adjust path to your DB connection file

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

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 12, 'Student Information Document', 0, 1, 'C');
$pdf->Ln(5);

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

$pdf->Ln(10);

// Footer note
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'R');

// Output
ob_end_clean();
$filename = 'Student_Info_' . str_replace('/', '_', $reg_no) . '.pdf';
$pdf->Output('D', $filename);
exit;
