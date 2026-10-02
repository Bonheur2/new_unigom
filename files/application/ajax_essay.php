<?php
include("../../meet/con.php");

$codestu     = $_REQUEST['codestu'] ?? '';
$question_id = (int) ($_REQUEST['question_id'] ?? 0);
$essay       = $_REQUEST['essay'] ?? '';

if ($codestu === '' || $question_id <= 0) {
    echo "fail";
    exit;
}

if (trim($essay) === '') {
    echo "empty";
    exit;
}

$check_sql = $conn->prepare("SELECT COUNT(*) FROM tbl_applicant_essays WHERE stu = :codestu AND question_id = :question_id");
$check_sql->execute(['codestu' => $codestu, 'question_id' => $question_id]);
$exists = $check_sql->fetchColumn() > 0;

if ($exists) {
    $stmt = $conn->prepare("UPDATE tbl_applicant_essays SET essay = :essay WHERE stu = :codestu AND question_id = :question_id");
} else {
    $stmt = $conn->prepare("INSERT INTO tbl_applicant_essays (stu, question_id, essay) VALUES (:codestu, :question_id, :essay)");
}

$result = $stmt->execute([
    'essay'       => $essay,
    'codestu'     => $codestu,
    'question_id' => $question_id,
]);

echo $result ? "Done" : "fail";