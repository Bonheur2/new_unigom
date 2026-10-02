<?php
// Include your database connection
include ('../../meet/con.php');


// Build filter conditions
$whereConditions = [];
$params = [];

if (!empty($_POST['from_date'])) {
    $whereConditions[] = "DATE(pt.recorded_date) >= ?";
    $params[] = $_POST['from_date'];
}

if (!empty($_POST['to_date'])) {
    $whereConditions[] = "DATE(pt.recorded_date) <= ?";
    $params[] = $_POST['to_date'];
}

if (!empty($_POST['channel'])) {
    if ($_POST['channel'] == 'SLCB') {
        $whereConditions[] = "pt.user = 'B-AGENT'";
    } else if ($_POST['channel'] == 'Africell') {
        $whereConditions[] = "pt.user != 'B-AGENT'";
    }
}

$whereClause = !empty($whereConditions) ? " WHERE " . implode(" AND ", $whereConditions) : "";

// Get statistics
$totalSql = $db->prepare("SELECT COUNT(*) as total_apps, SUM(amount) as total_amount FROM payment_trial pt" . $whereClause);
$totalSql->execute($params);
$totals = $totalSql->fetch();

// Get SLCB stats
$slcbWhere = $whereConditions;
$slcbParams = $params;
$slcbWhere[] = "pt.user = 'B-AGENT'";
$slcbSql = $db->prepare("SELECT COUNT(*) as slcb_count, SUM(amount) as slcb_amount FROM payment_trial pt WHERE " . implode(" AND ", $slcbWhere));
$slcbSql->execute($slcbParams);
$slcb = $slcbSql->fetch();

// Get Africell stats
$africellWhere = $whereConditions;
$africellParams = $params;
$africellWhere[] = "pt.user != 'B-AGENT'";
$africellSql = $db->prepare("SELECT COUNT(*) as africell_count, SUM(amount) as africell_amount FROM payment_trial pt WHERE " . implode(" AND ", $africellWhere));
$africellSql->execute($africellParams);
$africell = $africellSql->fetch();

// Get table data
$sql = $db->prepare("SELECT pt.*,ap.* FROM payment_trial pt
    INNER JOIN tbl_applicants ap ON pt.reg_no=ap.code" . $whereClause . " ORDER BY pt.recorded_date DESC");
$sql->execute($params);
$data = $sql->fetchAll();

// Prepare response
$response = [
    'stats' => [
        'total_apps' => $totals['total_apps'],
        'total_amount' => number_format($totals['total_amount']),
        'slcb_count' => $slcb['slcb_count'],
        'slcb_amount' => number_format($slcb['slcb_amount']),
        'africell_count' => $africell['africell_count'],
        'africell_amount' => number_format($africell['africell_amount'])
    ],
    'data' => $data
];

echo json_encode($response);
?>