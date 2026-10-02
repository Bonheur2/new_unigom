<?php
// Controller for Teaching_Units/index.php - registers teaching units (UE) and
// the courses (EC) inside each one.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__, 2));
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

// con.php sets error_reporting(0), so a failure would otherwise return an empty
// body and the caller's JSON.parse would fail with no message.
error_reporting(E_ALL);
ini_set('display_errors', '0');

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

set_exception_handler(function($e){
    if(!headers_sent()){
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'status'  => 500,
        'message' => 'Server error: '.$e->getMessage(),
        'where'   => basename($e->getFile()).':'.$e->getLine()
    ]);
    exit;
});

class TeachingUnits{
    private $connect;

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    // '' / '0' / missing -> null, so optional scope fields store NULL ("applies to all")
    private function nullify($v){
        return ($v === '' || $v === null || $v === '0') ? null : $v;
    }

    private function num($v, $default){
        $v = trim((string) $v);
        return ($v === '' || !is_numeric($v)) ? $default : $v;
    }

    // ------------------------------------------------------------ cascades

    public function get_programmes(){
        $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type
                                        WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
        $sql->execute([':fac_id' => $_POST['fac_id'] ?? '']);
        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function get_levels(){
        $sql = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level
                                        WHERE prg_type_id = :p AND status = 1 ORDER BY level_rank ASC");
        $sql->execute([':p' => $_POST['prg_type_id'] ?? '']);
        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function get_departments(){
        $sql = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department
                                        WHERE prg_type = :p AND status = 1 ORDER BY dept_full_name ASC");
        $sql->execute([':p' => $_POST['prg_type_id'] ?? '']);
        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    public function get_options(){
        $sql = $this->connect->prepare("SELECT opt_id, opt_full_name FROM tbl_option
                                        WHERE dept_id = :d AND status = 1 ORDER BY opt_full_name ASC");
        $sql->execute([':d' => $_POST['dept_id'] ?? '']);
        echo json_encode(['status' => 200, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    // ------------------------------------------------------------ units

    /** Reads and validates the unit fields shared by save and update. */
    private function read_unit(){
        $u = [
            'ue_code'     => strtoupper(trim($_POST['ue_code'] ?? '')),
            'ue_name'     => trim($_POST['ue_name'] ?? ''),
            'prg_type_id' => $this->nullify($_POST['prg_type_id'] ?? ''),
            'level_id'    => $this->nullify($_POST['level_id'] ?? ''),
            'semester_no' => (int) ($_POST['semester_no'] ?? 1),
            'dept_id'     => $this->nullify($_POST['dept_id'] ?? ''),
            'opt_id'      => $this->nullify($_POST['opt_id'] ?? ''),
            'credits'     => $this->num($_POST['credits'] ?? '', 0),
            'pass_mark'   => $this->num($_POST['pass_mark'] ?? '', 10)
        ];

        if($u['ue_code'] === '' || $u['ue_name'] === ''){ return 'Unit code and name are required'; }
        if($u['prg_type_id'] === null || $u['level_id'] === null){ return 'Programme and level are required'; }
        if(!in_array($u['semester_no'], [1, 2], true)){ return 'Semester must be 1 or 2'; }
        if($u['opt_id'] !== null && $u['dept_id'] === null){ return 'Choose the department when an option is selected'; }
        if($u['pass_mark'] < 0){ return 'Pass mark cannot be negative'; }
        if($u['credits'] < 0){ return 'Credits cannot be negative'; }

        return $u;
    }

    public function save_unit(){

        $u = $this->read_unit();
        if(is_string($u)){ echo json_encode(['status' => 401, 'message' => $u]); return; }

        try{
            $chk = $this->connect->prepare("SELECT ue_id FROM tbl_teaching_units WHERE ue_code = :c AND prg_type_id = :p");
            $chk->execute([':c' => $u['ue_code'], ':p' => $u['prg_type_id']]);
            if($chk->fetch()){
                echo json_encode(['status' => 401, 'message' => 'A unit with this code already exists in this programme']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_teaching_units
                (ue_code, ue_name, prg_type_id, level_id, semester_no, dept_id, opt_id, credits, pass_mark, status)
                VALUES (:ue_code, :ue_name, :prg_type_id, :level_id, :semester_no, :dept_id, :opt_id, :credits, :pass_mark, 1)");
            $sql->execute($this->bind($u));

            echo json_encode(['status' => 200, 'message' => 'Unit saved', 'ue_id' => $this->connect->lastInsertId()]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving unit: '.$e->getMessage()]);
        }
    }

    public function view_unit(){
        $sql = $this->connect->prepare("SELECT u.*, pt.fac_id
                                        FROM tbl_teaching_units u
                                        LEFT JOIN tbl_program_type pt ON pt.prg_type_id = u.prg_type_id
                                        WHERE u.ue_id = :id");
        $sql->execute([':id' => $_POST['ue_id'] ?? '']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row ? ['status' => 200, 'data' => $row] : ['status' => 401, 'message' => 'Unit not found']);
    }

    public function update_unit(){

        $ue_id = $_POST['ue_id'] ?? '';
        $u = $this->read_unit();
        if(is_string($u)){ echo json_encode(['status' => 401, 'message' => $u]); return; }

        try{
            $chk = $this->connect->prepare("SELECT ue_id FROM tbl_teaching_units
                                            WHERE ue_code = :c AND prg_type_id = :p AND ue_id != :id");
            $chk->execute([':c' => $u['ue_code'], ':p' => $u['prg_type_id'], ':id' => $ue_id]);
            if($chk->fetch()){
                echo json_encode(['status' => 401, 'message' => 'A unit with this code already exists in this programme']);
                return;
            }

            $sql = $this->connect->prepare("UPDATE tbl_teaching_units SET
                ue_code = :ue_code, ue_name = :ue_name, prg_type_id = :prg_type_id, level_id = :level_id,
                semester_no = :semester_no, dept_id = :dept_id, opt_id = :opt_id,
                credits = :credits, pass_mark = :pass_mark
                WHERE ue_id = :ue_id");
            $sql->execute(array_merge($this->bind($u), [':ue_id' => $ue_id]));

            echo json_encode(['status' => 200, 'message' => 'Unit updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating unit: '.$e->getMessage()]);
        }
    }

    public function toggle_unit(){
        $this->toggle('tbl_teaching_units', 'ue_id', $_POST['ue_id'] ?? '');
    }

    // ------------------------------------------------------------ courses

    public function load_courses(){
        $ue = $this->connect->prepare("SELECT ue_id, ue_code, ue_name, credits FROM tbl_teaching_units WHERE ue_id = :id");
        $ue->execute([':id' => $_POST['ue_id'] ?? '']);
        $unit = $ue->fetch(PDO::FETCH_ASSOC);
        if(!$unit){
            echo json_encode(['status' => 401, 'message' => 'Unit not found']);
            return;
        }

        $sql = $this->connect->prepare("SELECT * FROM tbl_courses WHERE ue_id = :id ORDER BY status DESC, course_name ASC");
        $sql->execute([':id' => $unit['ue_id']]);

        echo json_encode(['status' => 200, 'unit' => $unit, 'data' => $sql->fetchAll(PDO::FETCH_ASSOC)]);
    }

    /** Reads and validates the course fields shared by save and update. */
    private function read_course(){
        $c = [
            'ue_id'         => $this->nullify($_POST['ue_id'] ?? ''),
            'course_code'   => strtoupper(trim($_POST['course_code'] ?? '')),
            'course_name'   => trim($_POST['course_name'] ?? ''),
            'credits'       => $this->num($_POST['credits'] ?? '', 0),
            'hours'         => $this->nullify(trim($_POST['hours'] ?? '')),
            'cat_weight'    => $this->num($_POST['cat_weight'] ?? '', 40),
            'exam_weight'   => $this->num($_POST['exam_weight'] ?? '', 60),
            'pass_mark'     => $this->num($_POST['pass_mark'] ?? '', 10),
            'exam_min_mark' => $this->nullify(trim($_POST['exam_min_mark'] ?? ''))
        ];

        if($c['ue_id'] === null){ return 'Unit is missing'; }
        if($c['course_name'] === ''){ return 'Course name is required'; }
        if(abs(($c['cat_weight'] + $c['exam_weight']) - 100) > 0.001){ return 'CAT % and Exam % must add up to 100'; }
        if($c['pass_mark'] < 0){ return 'Pass mark cannot be negative'; }
        if($c['exam_min_mark'] !== null && $c['exam_min_mark'] < 0){ return 'Eliminatory exam mark cannot be negative'; }
        if($c['course_code'] === ''){ $c['course_code'] = null; }   // NULL is allowed to repeat under the UNIQUE key

        return $c;
    }

    public function save_course(){

        $c = $this->read_course();
        if(is_string($c)){ echo json_encode(['status' => 401, 'message' => $c]); return; }

        try{
            if($c['course_code'] !== null){
                $chk = $this->connect->prepare("SELECT course_id FROM tbl_courses WHERE course_code = :c");
                $chk->execute([':c' => $c['course_code']]);
                if($chk->fetch()){
                    echo json_encode(['status' => 401, 'message' => 'A course with this code already exists']);
                    return;
                }
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_courses
                (ue_id, course_code, course_name, credits, hours, cat_weight, exam_weight,
                 pass_mark, exam_min_mark, status)
                VALUES (:ue_id, :course_code, :course_name, :credits, :hours, :cat_weight, :exam_weight,
                        :pass_mark, :exam_min_mark, 1)");
            $sql->execute($this->bind($c));

            echo json_encode(['status' => 200, 'message' => 'Course saved']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving course: '.$e->getMessage()]);
        }
    }

    public function view_course(){
        $sql = $this->connect->prepare("SELECT * FROM tbl_courses WHERE course_id = :id");
        $sql->execute([':id' => $_POST['course_id'] ?? '']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row ? ['status' => 200, 'data' => $row] : ['status' => 401, 'message' => 'Course not found']);
    }

    public function update_course(){

        $course_id = $_POST['course_id'] ?? '';
        $c = $this->read_course();
        if(is_string($c)){ echo json_encode(['status' => 401, 'message' => $c]); return; }

        try{
            if($c['course_code'] !== null){
                $chk = $this->connect->prepare("SELECT course_id FROM tbl_courses WHERE course_code = :c AND course_id != :id");
                $chk->execute([':c' => $c['course_code'], ':id' => $course_id]);
                if($chk->fetch()){
                    echo json_encode(['status' => 401, 'message' => 'A course with this code already exists']);
                    return;
                }
            }

            $sql = $this->connect->prepare("UPDATE tbl_courses SET
                ue_id = :ue_id, course_code = :course_code, course_name = :course_name,
                credits = :credits, hours = :hours, cat_weight = :cat_weight, exam_weight = :exam_weight,
                pass_mark = :pass_mark, exam_min_mark = :exam_min_mark
                WHERE course_id = :course_id");
            $sql->execute(array_merge($this->bind($c), [':course_id' => $course_id]));

            echo json_encode(['status' => 200, 'message' => 'Course updated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating course: '.$e->getMessage()]);
        }
    }

    public function toggle_course(){
        $this->toggle('tbl_courses', 'course_id', $_POST['course_id'] ?? '');
    }

    // ------------------------------------------------------------ helpers

    private function bind($arr){
        $out = [];
        foreach($arr as $k => $v){ $out[':'.$k] = $v; }
        return $out;
    }

    /** Active <-> inactive. $table / $key are fixed by the callers, never user input. */
    private function toggle($table, $key, $id){
        try{
            $sql = $this->connect->prepare("SELECT status FROM $table WHERE $key = :id");
            $sql->execute([':id' => $id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                echo json_encode(['status' => 401, 'message' => 'Record not found']);
                return;
            }
            $upd = $this->connect->prepare("UPDATE $table SET status = :s WHERE $key = :id");
            $upd->execute([':s' => $row['status'] == 1 ? 0 : 1, ':id' => $id]);
            echo json_encode(['status' => 200, 'message' => $row['status'] == 1 ? 'Deactivated' : 'Activated']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating status: '.$e->getMessage()]);
        }
    }
}

$units = new TeachingUnits();
$action = $_POST['action'] ?? '';

switch($action){
    case 'get_programmes':  $units->get_programmes();  break;
    case 'get_levels':      $units->get_levels();      break;
    case 'get_departments': $units->get_departments(); break;
    case 'get_options':     $units->get_options();     break;
    case 'save_unit':       $units->save_unit();       break;
    case 'view_unit':       $units->view_unit();       break;
    case 'update_unit':     $units->update_unit();     break;
    case 'toggle_unit':     $units->toggle_unit();     break;
    case 'load_courses':    $units->load_courses();    break;
    case 'save_course':     $units->save_course();     break;
    case 'view_course':     $units->view_course();     break;
    case 'update_course':   $units->update_course();   break;
    case 'toggle_course':   $units->toggle_course();   break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
