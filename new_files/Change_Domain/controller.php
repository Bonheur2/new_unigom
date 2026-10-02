<?php
// Controller for the "Change of Domain" form (Change_Domain/index.php).
// For any logged-in applicant who wants to request switching Faculty /
// Programme / Department / Class. The request is recorded in
// tbl_domain_change_requests and reviewed separately by staff - it does not
// touch the applicant's existing tbl_admittedPRG row, if any.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__, 2));
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');


require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(LIB_PATH.DS."form_type.php");
require_once(LIB_PATH.DS."application_mail.php");

// con.php sets error_reporting(0), so anything that goes wrong below would
// otherwise return an empty body and the caller's JSON.parse would fail with no
// message. Report errors, but never print them - they are returned as JSON.
error_reporting(E_ALL);
ini_set('display_errors', '0');

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

// A missing column raises PDOException, but a bad call raises Error, which the
// per-action catch blocks (PDOException only) would let escape as a blank page.
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

class ChangeDomain{
    private $connect;
    private $form_mis = 'domaine';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function column_exists($table, $column){
        static $cache = [];
        $key = $table.'.'.$column;
        if(!isset($cache[$key])){
            try {
                $sql = $this->connect->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS
                                                WHERE TABLE_SCHEMA = DATABASE()
                                                AND TABLE_NAME = :t AND COLUMN_NAME = :c");
                $sql->execute([':t' => $table, ':c' => $column]);
                $cache[$key] = ((int) $sql->fetchColumn()) > 0;
            } catch(PDOException $e){
                $cache[$key] = false;
            }
        }
        return $cache[$key];
    }

    private function get_active_application_period(){
        $sql = $this->connect->prepare("SELECT id FROM tbl_application_periods
                                        WHERE status = 'active' AND start_date <= :today AND end_date >= :today
                                        ORDER BY created_at DESC LIMIT 1");
        $sql->execute([':today' => date('Y-m-d')]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : null;
    }

    private function get_applicant_by_identification($identification){
        $sql = $this->connect->prepare("SELECT applicant_id, fname, lname, email, application_code FROM tbl_applicants WHERE application_code = :identification");
        $sql->execute([':identification' => $identification]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    private function get_current_admitted_program($application_code){
        $sql = $this->connect->prepare("SELECT Aprg_id FROM tbl_admittedPRG WHERE Stu_code = :stu_code ORDER BY Aprg_id DESC LIMIT 1");
        $sql->execute([':stu_code' => $application_code]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    private function has_pending_change_request($application_code){
        $sql = $this->connect->prepare("SELECT request_id FROM tbl_domain_change_requests WHERE application_code = :application_code AND status = 'pending' LIMIT 1");
        $sql->execute([':application_code' => $application_code]);
        return $sql->rowCount() > 0;
    }

    public function submit_request(){
        $identification = $_SESSION['identification'] ?? '';

        if($identification === ''){
            echo json_encode(['status' => 401, 'message' => 'You must be logged in to request a change of domain']);
            return;
        }

        $applicant = $this->get_applicant_by_identification($identification);
        if(!$applicant){
            echo json_encode(['status' => 401, 'message' => 'Applicant record not found']);
            return;
        }

        $current = $this->get_current_admitted_program($applicant['application_code']);

        if($this->has_pending_change_request($applicant['application_code'])){
            echo json_encode(['status' => 401, 'message' => 'You already have a pending change of domain request. Please wait for it to be reviewed.']);
            return;
        }

        $dob = $_POST['dob'] ?? '';
        $place_of_birth = trim($_POST['place_of_birth'] ?? '');
        $country_of_birth = trim($_POST['country_of_birth'] ?? '');
        $nationality_id = $_POST['nationality'] ?? null;
        $father_name = trim($_POST['father_name'] ?? '');
        $mother_name = trim($_POST['mother_name'] ?? '');
        $blood_type = trim($_POST['blood_type'] ?? '');
        $marital_status = trim($_POST['marital_status'] ?? '');
        $religious_affiliation = trim($_POST['religious_affiliation'] ?? '');
        $parents_province_origin = trim($_POST['parents_province_origin'] ?? '');
        $country_id = $_POST['country'] ?? null;
        $parent_phone = trim($_POST['parent_phone'] ?? '');
        $ref_phone = trim($_POST['ref_phone'] ?? '');
        $territory_of_origin = trim($_POST['territory_of_origin'] ?? '');
        $candidate_address = trim($_POST['candidate_address'] ?? '');
        $secondary_school_name = trim($_POST['secondary_school_name'] ?? '');
        $secondary_school_province = trim($_POST['secondary_school_province'] ?? '');
        $secondary_school_territory = trim($_POST['secondary_school_territory'] ?? '');
        $secondary_school_territory_country = $_POST['secondary_school_territory_country'] ?? '';
        $humanities_section = trim($_POST['humanities_section'] ?? '');
        $secondary_school_status = trim($_POST['secondary_school_status'] ?? '') ?: null;
        $diploma_year = trim($_POST['diploma_year'] ?? '') ?: null;
        $diploma_percentage = trim($_POST['diploma_percentage'] ?? '') ?: null;
        $state_diploma_number = trim($_POST['state_diploma_number'] ?? '');

        $requested_fac_id = $_POST['fac_id_1'] ?? '';
        $requested_prg_type_id = $_POST['prg_type_id_1'] ?? '';
        $requested_dept_id = $_POST['dept_choice_1'] ?? '';
        $requested_level_id = $_POST['level_id'] ?? '';
        $requested_opt_id = $_POST['opt_choice_1'] ?? '';

        if($dob === '' || $father_name === '' || $mother_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if($requested_fac_id === '' || $requested_prg_type_id === '' || $requested_dept_id === '' || $requested_level_id === ''){
            echo json_encode(['status' => 401, 'message' => 'Please complete the faculty, programme, department and class you are applying for']);
            return;
        }

        try{
            $upd = $this->connect->prepare("UPDATE tbl_applicants SET
                                            dob = :dob, place_of_birth = :place_of_birth,
                                            country_of_birth = :country_of_birth,
                                            nationality_id = :nationality_id,
                                            father_name = :father_name, mother_name = :mother_name,
                                            blood_type = :blood_type, marital_status = :marital_status,
                                            religious_affiliation = :religious_affiliation,
                                            parents_province_origin = :parents_province_origin,
                                            country_id = :country_id,
                                            parent_phone = :parent_phone, ref_phone = :ref_phone,
                                            territory_of_origin = :territory_of_origin,
                                            candidate_address = :candidate_address,
                                            secondary_school_name = :secondary_school_name,
                                            secondary_school_province = :secondary_school_province,
                                            secondary_school_territory = :secondary_school_territory,
                                            secondary_school_territory_country = :secondary_school_territory_country,
                                            humanities_section = :humanities_section,
                                            secondary_school_status = :secondary_school_status,
                                            diploma_year = :diploma_year, diploma_percentage = :diploma_percentage,
                                            state_diploma_number = :state_diploma_number,
                                            form_id = :form_id,
                                            application_period_id = :application_period_id,
                                            updated_at = NOW()
                                            WHERE applicant_id = :id");
            $upd->execute([
                ':dob' => $dob,
                ':place_of_birth' => $place_of_birth,
                ':country_of_birth' => $country_of_birth,
                ':nationality_id' => $nationality_id ?: null,
                ':father_name' => $father_name,
                ':mother_name' => $mother_name,
                ':blood_type' => $blood_type,
                ':marital_status' => $marital_status,
                ':religious_affiliation' => $religious_affiliation,
                ':parents_province_origin' => $parents_province_origin,
                ':country_id' => $country_id ?: null,
                ':parent_phone' => $parent_phone,
                ':ref_phone' => $ref_phone,
                ':territory_of_origin' => $territory_of_origin,
                ':candidate_address' => $candidate_address,
                ':secondary_school_name' => $secondary_school_name,
                ':secondary_school_province' => $secondary_school_province,
                ':secondary_school_territory' => $secondary_school_territory,
                ':secondary_school_territory_country' => $secondary_school_territory_country ?: null,
                ':humanities_section' => $humanities_section,
                ':secondary_school_status' => $secondary_school_status,
                ':diploma_year' => $diploma_year,
                ':diploma_percentage' => $diploma_percentage,
                ':state_diploma_number' => $state_diploma_number,
                ':form_id' => resolve_form_id($this->connect, $this->form_mis),
                ':application_period_id' => $this->get_active_application_period(),
                ':id' => $applicant['applicant_id']
            ]);

            $this->save_prior_studies($applicant['applicant_id']);

            // requested_opt_id was added after this table shipped; skip it when the
            // column is not there yet so an un-migrated database still accepts the
            // request instead of failing the whole submission.
            $cols = [
                'applicant_id'            => $applicant['applicant_id'],
                'application_code'        => $applicant['application_code'],
                'current_admitted_prg_id' => $current ? $current['Aprg_id'] : null,
                'requested_fac_id'        => $requested_fac_id,
                'requested_prg_type_id'   => $requested_prg_type_id,
                'requested_dept_id'       => $requested_dept_id,
                'requested_level_id'      => $requested_level_id !== '' ? $requested_level_id : null
            ];
            if($this->column_exists('tbl_domain_change_requests', 'requested_opt_id')){
                $cols['requested_opt_id'] = $requested_opt_id !== '' ? $requested_opt_id : null;
            }

            $names  = array_keys($cols);
            $params = array_map(function($c){ return ':'.$c; }, $names);
            $ins = $this->connect->prepare("INSERT INTO tbl_domain_change_requests
                                            (".implode(', ', $names).", status)
                                            VALUES (".implode(', ', $params).", 'pending')");
            $bind = [];
            foreach($cols as $k => $v){ $bind[':'.$k] = $v; }
            $ins->execute($bind);

            // A failed email must never fail the submission - the helper logs and
            // returns false rather than throwing.
            send_domain_change_confirmation(
                $this->connect,
                $applicant['email'] ?? '',
                $applicant['fname'],
                $applicant['lname'],
                $applicant['application_code'],
                $requested_prg_type_id,
                $requested_dept_id,
                $requested_level_id
            );

            echo json_encode([
                'status' => 200,
                'message' => 'Your change of domain request has been submitted successfully and is pending review.'
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error submitting request: '.$e->getMessage()]);
        }
    }

    private function save_prior_studies($applicant_id){
        if(!isset($_POST['prior_studies']) || !is_array($_POST['prior_studies'])){
            return;
        }

        $years = $_POST['prior_studies']['year'] ?? [];
        $fields = $_POST['prior_studies']['field'] ?? [];
        $departments = $_POST['prior_studies']['department'] ?? [];
        $promotions = $_POST['prior_studies']['promotion'] ?? [];
        $percentages = $_POST['prior_studies']['percentage'] ?? [];
        $mentions = $_POST['prior_studies']['mention'] ?? [];
        $failures = $_POST['prior_studies']['failures'] ?? [];
        $sessions = $_POST['prior_studies']['session'] ?? [];

        $sql = $this->connect->prepare("INSERT INTO tbl_applicant_prior_studies
                                        (applicant_id, study_year, prior_field, prior_department, promotion, percentage, mention, number_of_failures, session)
                                        VALUES (:applicant_id, :study_year, :prior_field, :prior_department, :promotion, :percentage, :mention, :number_of_failures, :session)");

        foreach($years as $i => $year){
            $year = trim($year ?? '');
            $field = trim($fields[$i] ?? '');
            $department = trim($departments[$i] ?? '');

            if($year === '' && $field === '' && $department === ''){
                continue;
            }

            $sql->execute([
                ':applicant_id' => $applicant_id,
                ':study_year' => $year !== '' ? $year : null,
                ':prior_field' => $field !== '' ? $field : null,
                ':prior_department' => $department !== '' ? $department : null,
                ':promotion' => trim($promotions[$i] ?? '') ?: null,
                ':percentage' => trim($percentages[$i] ?? '') ?: null,
                ':mention' => trim($mentions[$i] ?? '') ?: null,
                ':number_of_failures' => trim($failures[$i] ?? '') ?: null,
                ':session' => trim($sessions[$i] ?? '') ?: null
            ]);
        }
    }

    public function load_faculties(){
        $sql = $this->connect->prepare("SELECT fac_id, fac_full_name FROM tbl_faculty WHERE status = 1 ORDER BY fac_full_name ASC");
        $sql->execute();
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function load_program_types_by_faculty(){
        $fac_id = $_POST['fac_id'] ?? '';

        $sql = $this->connect->prepare("SELECT prg_type_id, prg_type_full_name FROM tbl_program_type WHERE fac_id = :fac_id AND status = 1 ORDER BY prg_type_full_name ASC");
        $sql->execute([':fac_id' => $fac_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function load_departments(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';

        $sql = $this->connect->prepare("SELECT dept_id, dept_full_name FROM tbl_department WHERE prg_type = :prg_type_id AND status = 1 ORDER BY dept_full_name ASC");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function load_options(){
        $dept_id = $_POST['dept_id'] ?? '';

        $sql = $this->connect->prepare("SELECT opt_id, opt_full_name FROM tbl_option WHERE dept_id = :dept_id AND status = 1 ORDER BY opt_full_name ASC");
        $sql->execute([':dept_id' => $dept_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function load_levels_by_program_type(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';

        $sql = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE prg_type_id = :prg_type_id AND status = 1 ORDER BY level_rank ASC");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$change_domain = new ChangeDomain();
$action = $_POST['action'] ?? '';

switch($action){
    case 'submit_request':
        $change_domain->submit_request();
        break;
    case 'load_faculties':
        $change_domain->load_faculties();
        break;
    case 'load_program_types_by_faculty':
        $change_domain->load_program_types_by_faculty();
        break;
    case 'load_options':
        $change_domain->load_options();
        break;
    case 'load_departments':
        $change_domain->load_departments();
        break;
    case 'load_levels_by_program_type':
        $change_domain->load_levels_by_program_type();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
