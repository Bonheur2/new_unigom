<?php
// One-off importer: loads the French country names from EF.xlsx into tbl_country.
//
// Open in the browser:
//   /new_files/Import/import_countries.php              -> dry run, changes nothing
//   /new_files/Import/import_countries.php?run=insert   -> insert every name from the file
//
// This inserts whatever the file holds without checking what is already in the
// table, so run it against an empty tbl_country. Repeated names inside the file
// are still collapsed to one row each.

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

header('Content-Type: text/plain; charset=utf-8');

require_once(LIB_PATH.DS."con.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$xlsx_path = __DIR__.DS.'EF.xlsx';
if(!file_exists($xlsx_path)){
    $xlsx_path = SITE_ROOT.DS.'EF.xlsx';
}

// Reads every non-empty cell value from the sheet, whichever column it sits in.
// $skipped collects rows that produced nothing, so the dry run can show why a
// name in the file never made it into the table.
function read_country_names($path, &$skipped = null){
    $skipped = [];

    $zip = new ZipArchive();
    if($zip->open($path) !== true){
        throw new Exception('Could not open '.$path);
    }

    $strings = [];
    $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
    if($sharedXml){
        $doc = new DOMDocument();
        $doc->loadXML($sharedXml);
        foreach($doc->getElementsByTagName('si') as $si){
            $strings[] = $si->textContent;
        }
    }

    // sheet1.xml is the usual name, but a workbook whose first sheet was renamed
    // or reordered can store it elsewhere - fall back to the first worksheet part.
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    if(!$sheetXml){
        for($i = 0; $i < $zip->numFiles; $i++){
            $entry = $zip->getNameIndex($i);
            if(strpos($entry, 'xl/worksheets/') === 0 && substr($entry, -4) === '.xml'){
                $sheetXml = $zip->getFromName($entry);
                break;
            }
        }
    }
    $zip->close();
    if(!$sheetXml){
        throw new Exception('Worksheet not found inside the workbook');
    }

    $sdoc = new DOMDocument();
    $sdoc->loadXML($sheetXml);

    $names = [];
    $rowIndex = 0;
    foreach($sdoc->getElementsByTagName('row') as $row){
        $rowIndex++;
        if($rowIndex === 1){
            continue; // header: "French names"
        }

        $rowNum = $row->getAttribute('r') ?: (string) $rowIndex;
        $found  = false;

        foreach($row->getElementsByTagName('c') as $cell){
            $type = $cell->getAttribute('t');
            $val  = '';

            if($type === 'inlineStr'){
                // text stored inline rather than in sharedStrings
                $isNode = $cell->getElementsByTagName('is')->item(0);
                $val = $isNode ? $isNode->textContent : '';
            } else {
                $vNode = $cell->getElementsByTagName('v')->item(0);
                $val = $vNode ? $vNode->textContent : '';
                if($type === 's' && $val !== ''){
                    $val = $strings[(int) $val] ?? '';
                }
            }

            // normalise: non-breaking spaces and stray whitespace are common in
            // exported sheets and would otherwise create near-duplicate names
            $val = str_replace("\xc2\xa0", ' ', $val);
            $val = trim(preg_replace('/\s+/u', ' ', $val));

            if($val !== ''){
                $names[] = $val;
                $found = true;
                break; // first non-empty cell in the row is the name
            }
        }

        if(!$found){
            $skipped[] = $rowNum;
        }
    }
    return $names;
}

try{
    if(!file_exists($xlsx_path)){
        throw new Exception('EF.xlsx not found. Upload it next to this script (Import/) or at the site root.');
    }

    $skippedRows = [];
    $names = read_country_names($xlsx_path, $skippedRows);

    // case-insensitive de-dup, keeping the first spelling seen
    $unique = [];
    $seen = [];
    $dupes = [];
    foreach($names as $n){
        $key = mb_strtolower($n);
        if(isset($seen[$key])){
            $dupes[] = $n;
            continue;
        }
        $seen[$key] = true;
        $unique[] = $n;
    }

    echo "Source file : ".$xlsx_path."\n";
    echo "Names read  : ".count($names)." (".count($unique)." unique)\n";
    if($dupes){
        echo "Duplicates in the file (not inserted twice): ".count($dupes)."\n";
        foreach(array_slice($dupes, 0, 10) as $d){ echo "   = ".$d."\n"; }
        if(count($dupes) > 10){ echo "   ... and ".(count($dupes)-10)." more\n"; }
    }
    if($skippedRows){
        echo "Empty rows skipped: ".count($skippedRows)." (rows ".implode(', ', array_slice($skippedRows, 0, 15)).(count($skippedRows) > 15 ? ', ...' : '').")\n";
    }
    echo "\n";

    $already = (int) $conn->query("SELECT COUNT(*) FROM tbl_country")->fetchColumn();
    echo "tbl_country currently holds ".$already." row(s)\n\n";

    $mode = $_GET['run'] ?? '';

    if($mode === ''){
        echo "=== DRY RUN - nothing was changed ===\n\n";
        echo "Would insert all ".count($unique)." name(s) from the file (first 20 shown):\n";
        foreach(array_slice($unique, 0, 20) as $n){ echo "   + ".$n."\n"; }
        if(count($unique) > 20){ echo "   ... and ".(count($unique)-20)." more\n"; }
        echo "\nTo apply:\n";
        echo "   ?run=insert    insert all ".count($unique)." name(s)\n";
        if($already > 0){
            echo "\n   NOTE: the table is not empty (".$already." row(s) already there).\n";
            echo "   Inserting would add the file's names on top of them, creating duplicates.\n";
            echo "   Empty the table first if that is not what you want.\n";
        }
        return;
    }

    if($mode !== 'insert'){
        echo "Unknown mode '".htmlspecialchars($mode)."'. Use ?run=insert.\n";
        return;
    }

    $conn->beginTransaction();
    $inserted = 0;

    $ins = $conn->prepare("INSERT INTO tbl_country (cntr_name) VALUES (:name)");
    foreach($unique as $n){
        $ins->execute([':name' => $n]);
        $inserted++;
    }

    $conn->commit();

    echo "=== DONE ===\n";
    echo "Inserted ".$inserted." row(s).\n";
    echo "tbl_country now holds ".$conn->query("SELECT COUNT(*) FROM tbl_country")->fetchColumn()." row(s).\n";
    echo "\nDelete this script from the server now that it has run.\n";

} catch(Exception $e){
    if($conn->inTransaction()){
        $conn->rollBack();
    }
    echo "IMPORT FAILED: ".$e->getMessage()."\n";
}
