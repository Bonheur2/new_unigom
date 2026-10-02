<?php
/**
 * One-off importer: reads data.xlsx (Campus / Faculte / Niveau / Department / Option)
 * and builds the full chain:
 *
 *   University -> Campus -> Faculty -> Program Type -> Department -> Option
 *                                                  -> Level
 *
 * The sheet has no Program Type column, so it is derived from Niveau:
 *
 *   L0, L1, L2, L3              -> Licence
 *   M1, M2, M3, M4              -> Master
 *   Specialisation Nème année   -> Specialisation
 *   Master complementaire       -> Master complementaire
 *
 * A Program Type is unique per Faculty: a faculty offering L1/L2/L3 gets ONE
 * "Licence" row, not three. Departments then hang off the program type, so the
 * same department repeated across L1/L2/L3 is created once.
 *
 * Levels hang off the program type (tbl_level.prg_type_id) with level_rank taken
 * from the Niveau digit, so a faculty's Licence ladder (L1..L3) and Master ladder
 * (M1..M2) stay separate and "first year" lookups (level_rank = 1) resolve to the
 * right one.
 *
 * Run once from the browser (while logged in) or via CLI: php import_data.php
 * Safe to re-run: every insert is guarded by a "does this already exist" check,
 * so running it twice will not create duplicates.
 */

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: text/plain; charset=utf-8');

$default_university_name = 'UNIGOM';
$xlsx_path = __DIR__.DS.'data.xlsx';

$stats = [
    'university' => ['created' => 0, 'existing' => 0],
    'campus' => ['created' => 0, 'existing' => 0],
    'faculty' => ['created' => 0, 'existing' => 0],
    'level' => ['created' => 0, 'existing' => 0],
    'program_type' => ['created' => 0, 'existing' => 0],
    'department' => ['created' => 0, 'existing' => 0],
    'option' => ['created' => 0, 'existing' => 0],
];
$errors = [];

/**
 * Maps a Niveau cell to its Program Type name, or null when unrecognised.
 * Accent- and spacing-tolerant, since the sheet mixes "1ère"/"2ème" and has
 * double spaces in places.
 */
function program_type_for_level($niveau){
    $n = trim($niveau);
    if($n === ''){
        return null;
    }

    // "Master complementaire" must be tested before the generic /^M\d/ rule.
    if(stripos($n, 'complementaire') !== false || stripos($n, 'complémentaire') !== false){
        return 'Master complementaire';
    }
    if(stripos($n, 'specialisation') !== false || stripos($n, 'spécialisation') !== false){
        return 'Specialisation';
    }
    if(preg_match('/^L\s*\d/i', $n)){
        return 'Licence';
    }
    if(preg_match('/^M\s*\d/i', $n)){
        return 'Master';
    }

    return null;
}

/**
 * Extracts the year number from a Niveau so tbl_level.level_rank is meaningful:
 * L1 -> 1, M2 -> 2, "Specialisation 3ème année" -> 3. Defaults to 1 when the
 * Niveau carries no digit (e.g. a bare "Master complementaire").
 *
 * $shift is set for faculties whose ladder starts at L0 (a Preparatoire year):
 * there L0 becomes rank 1, L1 rank 2 and so on, because the application forms
 * resolve a starting level with level_rank = 1 and would otherwise find none.
 */
function level_rank_for_level($niveau, $shift = 0){
    if(preg_match('/(\d+)/', $niveau, $m)){
        return ((int) $m[1]) + $shift;
    }
    return 1;
}

function col_to_index($col){
    $col = preg_replace('/[0-9]/', '', $col);
    $index = 0;
    for($i = 0; $i < strlen($col); $i++){
        $index = $index * 26 + (ord($col[$i]) - ord('A') + 1);
    }
    return $index - 1;
}

function read_sheet_rows($xlsx_path, $sheet_file = 'xl/worksheets/sheet1.xml'){
    $zip = new ZipArchive();
    if($zip->open($xlsx_path) !== true){
        throw new Exception('Could not open '.$xlsx_path);
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

    $sheetXml = $zip->getFromName($sheet_file);
    $zip->close();
    if(!$sheetXml){
        throw new Exception('Sheet not found: '.$sheet_file);
    }

    $sdoc = new DOMDocument();
    $sdoc->loadXML($sheetXml);
    $rowNodes = $sdoc->getElementsByTagName('row');

    $rows = [];
    $rowIndex = 0;
    foreach($rowNodes as $row){
        $rowIndex++;
        if($rowIndex == 1) continue; // header row

        $cells = $row->getElementsByTagName('c');
        $rowData = [];
        foreach($cells as $cell){
            $ref = $cell->getAttribute('r');
            $type = $cell->getAttribute('t');
            $vNode = $cell->getElementsByTagName('v')->item(0);
            $val = $vNode ? $vNode->textContent : '';
            if($type === 's' && $val !== ''){
                $val = $strings[(int) $val];
            }
            $rowData[col_to_index($ref)] = $val;
        }

        // Column A (#) is often absent for continuation rows, so index by
        // the *count* of populated leading columns is unreliable - the sheet
        // actually uses B=#, C=Campus, D=Faculte, E=Niveau, F=Department, G=Option.
        $rows[] = [
            'campus' => isset($rowData[2]) ? trim($rowData[2]) : '',
            'faculty' => isset($rowData[3]) ? trim($rowData[3]) : '',
            'level' => isset($rowData[4]) ? trim($rowData[4]) : '',
            'department' => isset($rowData[5]) ? trim($rowData[5]) : '',
            'option' => isset($rowData[6]) ? trim($rowData[6]) : '',
        ];
    }
    return $rows;
}

function get_or_create_university($conn, $name, &$stats){
    $sql = $conn->prepare("SELECT id FROM tbl_university WHERE full_name = :name");
    $sql->execute([':name' => $name]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['university']['existing']++;
        return $row['id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_university (full_name) VALUES (:name)");
    $ins->execute([':name' => $name]);
    $stats['university']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_campus($conn, $university_id, $camp_full_name, &$stats){
    $sql = $conn->prepare("SELECT camp_id FROM tbl_campus WHERE camp_full_name = :name AND university_id = :uid");
    $sql->execute([':name' => $camp_full_name, ':uid' => $university_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['campus']['existing']++;
        return $row['camp_id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_campus (university_id, camp_full_name, camp_active) VALUES (:uid, :name, 1)");
    $ins->execute([':uid' => $university_id, ':name' => $camp_full_name]);
    $stats['campus']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_faculty($conn, $campus_id, $fac_full_name, &$stats){
    $sql = $conn->prepare("SELECT fac_id FROM tbl_faculty WHERE fac_full_name = :name AND campus_id = :cid");
    $sql->execute([':name' => $fac_full_name, ':cid' => $campus_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['faculty']['existing']++;
        return $row['fac_id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_faculty (campus_id, fac_full_name, status) VALUES (:cid, :name, 1)");
    $ins->execute([':cid' => $campus_id, ':name' => $fac_full_name]);
    $stats['faculty']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_level($conn, $prg_type_id, $level_full_name, $rank_shift, &$stats){
    $sql = $conn->prepare("SELECT level_id FROM tbl_level WHERE level_full_name = :name AND prg_type_id = :pid");
    $sql->execute([':name' => $level_full_name, ':pid' => $prg_type_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['level']['existing']++;
        return $row['level_id'];
    }

    $rank = level_rank_for_level($level_full_name, $rank_shift);

    $ins = $conn->prepare("INSERT INTO tbl_level (prg_type_id, level_no, level_full_name, level_rank, status) VALUES (:pid, :no, :name, :rank, 1)");
    $ins->execute([
        ':pid'  => $prg_type_id,
        ':no'   => $rank,
        ':name' => $level_full_name,
        ':rank' => $rank
    ]);
    $stats['level']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_program_type($conn, $fac_id, $prg_type_full_name, &$stats){
    $sql = $conn->prepare("SELECT prg_type_id FROM tbl_program_type WHERE prg_type_full_name = :name AND fac_id = :fid");
    $sql->execute([':name' => $prg_type_full_name, ':fid' => $fac_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['program_type']['existing']++;
        return $row['prg_type_id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_program_type (fac_id, prg_type_full_name, status) VALUES (:fid, :name, 1)");
    $ins->execute([':fid' => $fac_id, ':name' => $prg_type_full_name]);
    $stats['program_type']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_department($conn, $prg_type_id, $dept_full_name, &$stats){
    $sql = $conn->prepare("SELECT dept_id FROM tbl_department WHERE dept_full_name = :name AND prg_type = :pid");
    $sql->execute([':name' => $dept_full_name, ':pid' => $prg_type_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['department']['existing']++;
        return $row['dept_id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_department (prg_type, dept_full_name, status) VALUES (:pid, :name, 1)");
    $ins->execute([':pid' => $prg_type_id, ':name' => $dept_full_name]);
    $stats['department']['created']++;
    return $conn->lastInsertId();
}

function get_or_create_option($conn, $dept_id, $opt_full_name, &$stats){
    $sql = $conn->prepare("SELECT opt_id FROM tbl_option WHERE opt_full_name = :name AND dept_id = :did");
    $sql->execute([':name' => $opt_full_name, ':did' => $dept_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row){
        $stats['option']['existing']++;
        return $row['opt_id'];
    }

    $ins = $conn->prepare("INSERT INTO tbl_option (dept_id, opt_full_name, status) VALUES (:did, :name, 1)");
    $ins->execute([':did' => $dept_id, ':name' => $opt_full_name]);
    $stats['option']['created']++;
    return $conn->lastInsertId();
}

try{
    if(!file_exists($xlsx_path)){
        throw new Exception('data.xlsx not found at '.$xlsx_path);
    }

    $rows = read_sheet_rows($xlsx_path);
    echo "Read ".count($rows)." data rows from data.xlsx\n\n";

    // Pre-pass: find faculty+programType ladders that start at L0 (a Preparatoire
    // year). Those shift by 1 so the entry level is rank 1, which is what the
    // application forms look for. Keyed per program type, so a Licence starting
    // at L0 does not shift that faculty's Master ladder.
    $ladder_rank_shift = [];
    $scan_fac = null;
    foreach($rows as $r){
        if($r['faculty'] !== ''){
            $scan_fac = $r['faculty'];
        }
        if($scan_fac !== null && preg_match('/^L\s*0\b/i', trim($r['level']))){
            $pt = program_type_for_level($r['level']);
            if($pt !== null){
                $ladder_rank_shift[$scan_fac.'||'.$pt] = 1;
            }
        }
    }
    if($ladder_rank_shift){
        echo "Ladders starting at L0 (level ranks shifted by 1):\n";
        foreach(array_keys($ladder_rank_shift) as $k){
            echo "  - ".str_replace('||', ' / ', $k)."\n";
        }
        echo "\n";
    }

    $university_id = get_or_create_university($conn, $default_university_name, $stats);

    $current_campus_id = null;
    $current_fac_id = null;
    $current_fac_name = null;

    // Program types are cached per "facultyId||typeName" so each faculty gets
    // exactly one Licence / Master / Specialisation / Master complementaire row,
    // however many Niveau rows map onto it.
    $prg_type_cache = [];
    $unmapped_levels = [];

    $conn->beginTransaction();

    foreach($rows as $i => $r){
        $line = $i + 2; // account for header + 1-based

        try{
            // Fill down merged campus/faculty cells
            if($r['campus'] !== ''){
                $current_campus_id = get_or_create_campus($conn, $university_id, $r['campus'], $stats);
                $current_fac_id = null; // force faculty re-resolve when campus changes
            }
            if($r['faculty'] !== ''){
                if($current_campus_id === null){
                    throw new Exception("Row $line: faculty '{$r['faculty']}' has no campus context");
                }
                $current_fac_id = get_or_create_faculty($conn, $current_campus_id, $r['faculty'], $stats);
                $current_fac_name = $r['faculty'];
            }

            if($current_campus_id === null || $current_fac_id === null){
                throw new Exception("Row $line: missing campus/faculty context, skipping");
            }

            // Program type comes from the Niveau, and is reused per faculty.
            // Resolved first because levels now hang off the program type.
            $prg_type_name = program_type_for_level($r['level']);
            if($prg_type_name === null){
                if($r['level'] !== ''){
                    $unmapped_levels[$r['level']] = true;
                }
                throw new Exception("Row $line: Niveau '{$r['level']}' does not map to a program type");
            }

            $cache_key = $current_fac_id.'||'.$prg_type_name;
            if(!isset($prg_type_cache[$cache_key])){
                $prg_type_cache[$cache_key] = get_or_create_program_type($conn, $current_fac_id, $prg_type_name, $stats);
            }
            $prg_type_id = $prg_type_cache[$cache_key];

            if($r['level'] !== ''){
                $shift = $ladder_rank_shift[$current_fac_name.'||'.$prg_type_name] ?? 0;
                get_or_create_level($conn, $prg_type_id, $r['level'], $shift, $stats);
            }

            if($r['department'] === ''){
                continue; // nothing more to do for this row
            }

            $dept_id = get_or_create_department($conn, $prg_type_id, $r['department'], $stats);

            // Option: blank or literal "N/A" -> option named after the department itself
            $opt_name = $r['option'];
            if($opt_name === '' || strtoupper($opt_name) === 'N/A'){
                $opt_name = $r['department'];
            }
            get_or_create_option($conn, $dept_id, $opt_name, $stats);

        } catch(Exception $rowEx){
            $errors[] = $rowEx->getMessage();
        }
    }

    $conn->commit();

    echo "=== Import complete ===\n\n";
    foreach($stats as $entity => $counts){
        echo str_pad(ucfirst(str_replace('_', ' ', $entity)), 15)." created: {$counts['created']}, already existed: {$counts['existing']}\n";
    }

    echo "\n=== Program types per faculty ===\n";
    $ptSql = $conn->query("SELECT tbl_faculty.fac_full_name, tbl_program_type.prg_type_id, tbl_program_type.prg_type_full_name,
                                  (SELECT COUNT(*) FROM tbl_department WHERE tbl_department.prg_type = tbl_program_type.prg_type_id) AS dept_count,
                                  (SELECT GROUP_CONCAT(level_full_name ORDER BY level_rank SEPARATOR ', ')
                                     FROM tbl_level WHERE tbl_level.prg_type_id = tbl_program_type.prg_type_id) AS levels
                             FROM tbl_program_type
                             INNER JOIN tbl_faculty ON tbl_program_type.fac_id = tbl_faculty.fac_id
                            ORDER BY tbl_faculty.fac_full_name, tbl_program_type.prg_type_full_name");
    $lastFac = null;
    foreach($ptSql as $pt){
        if($pt['fac_full_name'] !== $lastFac){
            echo "\n  ".$pt['fac_full_name']."\n";
            $lastFac = $pt['fac_full_name'];
        }
        echo "     - ".str_pad($pt['prg_type_full_name'], 26)." (".$pt['dept_count']." dept)  levels: ".($pt['levels'] ?: '-')."\n";
    }

    if(!empty($unmapped_levels)){
        echo "\n=== Niveau values with no program type mapping ===\n";
        foreach(array_keys($unmapped_levels) as $lv){
            echo "- '".$lv."'  <-- add a rule in program_type_for_level()\n";
        }
    }

    if(!empty($errors)){
        echo "\n=== Warnings (".count($errors)." row(s) skipped) ===\n";
        foreach($errors as $e){
            echo "- $e\n";
        }
    } else {
        echo "\nNo warnings.\n";
    }

} catch(Exception $e){
    if($conn->inTransaction()){
        $conn->rollBack();
    }
    echo "IMPORT FAILED: ".$e->getMessage()."\n";
}
