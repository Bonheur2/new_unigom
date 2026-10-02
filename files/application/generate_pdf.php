<?php
require('../../fpdf/fpdf.php'); // Make sure to include FPDF library
include ('../../meet/con.php');
// include 'your_db_connection.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo - place your logo file in the same directory or specify the correct path
        if (file_exists('../../img/logo/NJALA.png')) {
            $this->Image('../../img/logo/NJALA.png', 10, 6, 30); // x, y, width
        }
        
        // Move to the right for title
        $this->SetXY(50, 10);
        $this->SetFont('Arial','B',16);
        $this->Cell(0,8,'Njala University Student Payment Report From MIS',0,1,'C');
        
        $this->Ln(5);
        
        // Add filter information
        global $filterInfo;
        if (!empty($filterInfo)) {
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,$filterInfo,0,1,'C');
            $this->Ln(5);
        }
        
        // Add some space after header
        $this->Ln(5);
        
        // Table header
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(200,220,255);
        $this->Cell(15,8,'#',1,0,'C',true);
        $this->Cell(35,8,'Tracking No',1,0,'C',true);
        $this->Cell(50,8,'Applicant Name',1,0,'C',true);
        $this->Cell(30,8,'Channel',1,0,'C',true);
        $this->Cell(25,8,'Amount',1,0,'C',true);
        $this->Cell(35,8,'Date',1,1,'C',true);
    }
    
    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Build filter conditions
$whereConditions = [];
$params = [];
$filterInfo = '';

if (!empty($_POST['from_date'])) {
    $whereConditions[] = "DATE(pt.recorded_date) >= ?";
    $params[] = $_POST['from_date'];
    $filterInfo .= 'From: ' . $_POST['from_date'] . ' ';
}

if (!empty($_POST['to_date'])) {
    $whereConditions[] = "DATE(pt.recorded_date) <= ?";
    $params[] = $_POST['to_date'];
    $filterInfo .= 'To: ' . $_POST['to_date'] . ' ';
}

if (!empty($_POST['channel'])) {
    if ($_POST['channel'] == 'SLCB') {
        $whereConditions[] = "pt.user = 'B-AGENT'";
    } else if ($_POST['channel'] == 'Africell') {
        $whereConditions[] = "pt.user != 'B-AGENT'";
    }
    $filterInfo .= 'Channel: ' . $_POST['channel'];
}

if (empty($filterInfo)) {
    $filterInfo = 'All Applications';
}

$whereClause = !empty($whereConditions) ? " WHERE " . implode(" AND ", $whereConditions) : "";

// Get data
$sql = $conn->prepare("SELECT pt.*,ap.* FROM payment_trial pt
    INNER JOIN tbl_applicants ap ON pt.reg_no=ap.code" . $whereClause . " ORDER BY pt.recorded_date DESC");
$sql->execute($params);
$data = $sql->fetchAll();

// Get statistics
$totalSql = $conn->prepare("SELECT COUNT(*) as total_apps, SUM(amount) as total_amount FROM payment_trial pt" . $whereClause);
$totalSql->execute($params);
$totals = $totalSql->fetch();

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);

$i = 1;
foreach($data as $row) {
    $pdf->Cell(15,6,$i++,1,0,'C');
    $pdf->Cell(35,6,$row['code'],1,0,'C');
    $pdf->Cell(50,6,$row['fname'].' '.$row['lname'],1,0,'L');
    $pdf->Cell(30,6,($row['user'] == 'B-AGENT') ? 'SLCB' : 'Africell',1,0,'C');
    $pdf->Cell(25,6,$row['amount'].' NLE',1,0,'R');
    $pdf->Cell(35,6,$row['recorded_date'],1,1,'C');
}

// Add summary
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Summary',0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Total Applications: '.$totals['total_apps'],0,1,'L');
$pdf->Cell(0,6,'Total Amount: '.number_format($totals['total_amount']).' NLE',0,1,'L');

// Output PDF
$filename = 'applications_report_' . date('Y-m-d_H-i-s') . '.pdf';
$pdf->Output('D', $filename);
?>