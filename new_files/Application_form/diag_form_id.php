<?php
// TEMPORARY DIAGNOSTIC - delete after use.
// Bypasses con.php's error_reporting(0) so real errors are visible, and reports
// whether form_type.php loads, whether tbl_applicants.form_id exists, and what
// resolve_form_id() returns for each form folder.
//
// Open in the browser: /new_files/Application_form/diag_form_id.php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

header('Content-Type: text/plain; charset=utf-8');

require_once(LIB_PATH.DS."con.php");

// con.php silences everything - turn it back on for this page only.
error_reporting(E_ALL);
ini_set('display_errors', '1');

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "LIB_PATH: ".LIB_PATH."\n";
echo "form_type.php exists: ".(file_exists(LIB_PATH.DS."form_type.php") ? 'YES' : 'NO')."\n\n";

require_once(LIB_PATH.DS."form_type.php");
echo "resolve_form_id() defined: ".(function_exists('resolve_form_id') ? 'YES' : 'NO')."\n\n";

try {
    $cols = $conn->query("SHOW COLUMNS FROM tbl_applicants LIKE 'form_id'")->fetchAll(PDO::FETCH_ASSOC);
    echo "tbl_applicants.form_id column: ".(count($cols) ? 'EXISTS' : 'MISSING  <-- run the ALTER TABLE')."\n\n";
} catch (Exception $e) {
    echo "Column check failed: ".$e->getMessage()."\n\n";
}

try {
    echo "=== tbl_form_types rows ===\n";
    $rows = $conn->query("SELECT form_id, form_name, form_path, status FROM tbl_form_types ORDER BY form_id")->fetchAll(PDO::FETCH_ASSOC);
    if(!$rows){
        echo "(no rows - nothing for resolve_form_id to match against)\n";
    }
    foreach($rows as $r){
        echo "  form_id={$r['form_id']}  status={$r['status']}  path='{$r['form_path']}'  name='{$r['form_name']}'\n";
    }

    echo "\n=== resolve_form_id() per mis value ===\n";
    $expected = [
        'newapplicant' => 'Application_form',
        'Postgraduate' => 'Postgraduate',
        'Master'       => 'Master',
        'domaine'      => 'Change_Domain',
        'Transifer'    => 'Transfer_Student',
    ];
    foreach($expected as $mis => $folder){
        $id = resolve_form_id($conn, $mis);
        echo "  ".str_pad($mis, 14)." (".str_pad($folder, 17).") => ".($id === null ? 'NULL  <-- no form_path ends with mis='.$mis : $id)."\n";
    }
} catch (Exception $e) {
    echo "Query failed: ".$e->getMessage()."\n";
}
