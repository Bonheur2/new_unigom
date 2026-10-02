<?php
// Controller for the "Transfer from another school/university" form
// (Transfer_Student/index.php). For a logged-in applicant (created via
// Create_account) submitting their first UNIGOM application, having
// previously studied elsewhere. Unlike new_appliaction.php's applicants
// (who always start at first-year level), a transfer student picks their own
// target Class/Level, since they may be transferring in above first year.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");
require_once(LIB_PATH.DS."form_type.php");
require_once(LIB_PATH.DS."application_mail.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class TransferStudent{
    private $connect;
    private $upload_dir = 'uploads/applicant_documents/';
    private $form_mis = 'Transifer';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function get_applicant_by_identification($identification){
        $sql = $this->connect->prepare("SELECT applicant_id, fname, lname, email, application_code FROM tbl_applicants WHERE application_code = :identification");
        $sql->execute([':identification' => $identification]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    private function has_admitted_program($application_code){
        $sql = $this->connect->prepare("SELECT Aprg_id FROM tbl_admittedPRG WHERE Stu_code = :stu_code LIMIT 1");
        $sql->execute([':stu_code' => $application_code]);
        return $sql->rowCount() > 0;
    }

    private function has_active_application_period(){
        return $this->get_active_application_period() !== null;
    }

    private function get_active_application_period(){
        $sql = $this->connect->prepare("SELECT id FROM tbl_application_periods
                                        WHERE status = 'active' AND start_date <= :today AND end_date >= :today
                                        ORDER BY created_at DESC LIMIT 1");
        $sql->execute([':today' => date('Y-m-d')]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : null;
    }

    private function get_active_academic_cycle_id(){
        $sql = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status = 1 ORDER BY acad_cycle_id DESC LIMIT 1");
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['acad_cycle_id'] : null;
    }

    private function get_campus_for_faculty($fac_id){
        $sql = $this->connect->prepare("SELECT campus_id FROM tbl_faculty WHERE fac_id = :fac_id");
        $sql->execute([':fac_id' => $fac_id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['campus_id'] : null;
    }

    public function complete_application(){
        $identification = $_SESSION['identification'] ?? '';

        if($identification === ''){
            echo json_encode(['status' => 401, 'message' => 'You must be logged in to continue your application']);
            return;
        }

        if(!$this->has_active_application_period()){
            echo json_encode(['status' => 401, 'message' => 'The application period is closed. You can no longer submit an application.']);
            return;
        }

        $applicant = $this->get_applicant_by_identification($identification);
        if(!$applicant){
            echo json_encode(['status' => 401, 'message' => 'Applicant record not found']);
            return;
        }

        if($this->has_admitted_program($applicant['application_code'])){
            echo json_encode(['status' => 401, 'message' => 'You have already submitted your application. Editing is not available yet.']);
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
        $secondary_school_territory_country = $_POST['secondary_school_territory_country'] ?? null;
        $humanities_section = trim($_POST['humanities_section'] ?? '');
        $secondary_school_status = trim($_POST['secondary_school_status'] ?? '') ?: null;
        $diploma_year = trim($_POST['diploma_year'] ?? '') ?: null;
        $diploma_percentage = trim($_POST['diploma_percentage'] ?? '') ?: null;
        $state_diploma_number = trim($_POST['state_diploma_number'] ?? '');

        $fac_id = $_POST['fac_id_1'] ?? '';
        $prg_type_id = $_POST['prg_type_id_1'] ?? '';
        $dept_id = $_POST['dept_choice_1'] ?? '';
        $opt_id = $_POST['opt_choice_1'] ?? '';
        $level_id = $_POST['level_id'] ?? '';

        if($dob === '' || $father_name === '' || $mother_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if($fac_id === '' || $prg_type_id === '' || $dept_id === '' || $level_id === ''){
            echo json_encode(['status' => 401, 'message' => 'Please complete the field, programme, department, and class you are applying for']);
            return;
        }

        // The dropdown only offers levels above first year, but re-check here:
        // the posted id could be anything.
        if(!$this->is_valid_transfer_level($level_id, $prg_type_id)){
            echo json_encode(['status' => 401, 'message' => 'Please choose a class above the first year. First-year applicants should use the ordinary application form.']);
            return;
        }

        try{
            $camp_id = $this->get_campus_for_faculty($fac_id);
            if($camp_id === null){
                echo json_encode(['status' => 401, 'message' => 'Invalid faculty selected']);
                return;
            }

            $acad_id = $this->get_active_academic_cycle_id();
            if($acad_id === null){
                echo json_encode(['status' => 401, 'message' => 'No active academic year is configured. Please contact admissions support.']);
                return;
            }

            $upd = $this->connect->prepare("UPDATE tbl_applicants SET
                                            dob = :dob, place_of_birth = :place_of_birth,
                                            country_of_birth = :country_of_birth,
                                            nationality_id = :nationality_id,
                                            father_name = :father_name, mother_name = :mother_name,
                                            blood_type = :blood_type, marital_status = :marital_status,
                                            religious_affiliation = :religious_affiliation,
                                            parents_province_origin = :parents_province_origin,
                                            country_id = :country_id,
                                            parent_phone = :parent_phone,
                                            ref_phone = :ref_phone,
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
                                            camp_id = :camp_id, prg_type_id = :prg_type_id,
                                            form_id = :form_id,
                                            application_period_id = :application_period_id,
                                            status = 'verified', updated_at = NOW()
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
                ':camp_id' => $camp_id,
                ':prg_type_id' => $prg_type_id,
                ':form_id' => resolve_form_id($this->connect, $this->form_mis),
                ':application_period_id' => $this->get_active_application_period(),
                ':id' => $applicant['applicant_id']
            ]);

            $this->insert_admitted_program($applicant['application_code'], $camp_id, $fac_id, $prg_type_id, $dept_id, $opt_id, $level_id, $acad_id);
            $this->save_documents($applicant['applicant_id'], $prg_type_id);
            $this->save_prior_studies($applicant['applicant_id']);

            send_application_confirmation(
                $this->connect,
                $applicant['email'] ?? '',
                $applicant['fname'],
                $applicant['lname'],
                $applicant['application_code'],
                $camp_id,
                $prg_type_id
            );

            echo json_encode([
                'status' => 200,
                'message' => 'Your application was submitted successfully. Your application code is '.$applicant['application_code'].'.',
                'application_code' => $applicant['application_code']
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error submitting application: '.$e->getMessage()]);
        }
    }

    private function insert_admitted_program($application_code, $camp_id, $fac_id, $prg_type_id, $dept_id, $opt_id, $level_id, $acad_id){
        $sql = $this->connect->prepare("INSERT INTO tbl_admittedPRG (Stu_code, cump_id, intake_id, prg_type, dept_id, level, mode, fac_id, splz, acad_id, sts)
                                        VALUES (:stu_code, :cump_id, :intake_id, :prg_type, :dept_id, :level, :mode, :fac_id, :splz, :acad_id, 0)");
        $sql->execute([
            ':stu_code' => $application_code,
            ':cump_id' => $camp_id,
            ':intake_id' => null,
            ':prg_type' => $prg_type_id,
            ':dept_id' => $dept_id,
            ':level' => $level_id,
            ':mode' => null,
            ':fac_id' => $fac_id,
            ':splz' => $opt_id ?: null,
            ':acad_id' => $acad_id
        ]);
    }

    private function save_prior_studies($applicant_id){
        if(!isset($_POST['prior_studies']) || !is_array($_POST['prior_studies'])){
            return;
        }

        $years = $_POST['prior_studies']['year'] ?? [];
        $establishments = $_POST['prior_studies']['establishment'] ?? [];
        $promotions = $_POST['prior_studies']['promotion'] ?? [];
        $percentages = $_POST['prior_studies']['percentage'] ?? [];
        $mentions = $_POST['prior_studies']['mention'] ?? [];
        $jury_decisions = $_POST['prior_studies']['jury_decision'] ?? [];
        $credits = $_POST['prior_studies']['credits'] ?? [];
        $sessions = $_POST['prior_studies']['session'] ?? [];

        $sql = $this->connect->prepare("INSERT INTO tbl_applicant_prior_studies
                                        (applicant_id, study_year, establishment, promotion, percentage, mention, jury_decision, non_capitalized_credits, session)
                                        VALUES (:applicant_id, :study_year, :establishment, :promotion, :percentage, :mention, :jury_decision, :non_capitalized_credits, :session)");

        foreach($years as $i => $year){
            $year = trim($year ?? '');
            $establishment = trim($establishments[$i] ?? '');

            if($year === '' && $establishment === ''){
                continue;
            }

            $sql->execute([
                ':applicant_id' => $applicant_id,
                ':study_year' => $year !== '' ? $year : null,
                ':establishment' => $establishment !== '' ? $establishment : null,
                ':promotion' => trim($promotions[$i] ?? '') ?: null,
                ':percentage' => trim($percentages[$i] ?? '') ?: null,
                ':mention' => trim($mentions[$i] ?? '') ?: null,
                ':jury_decision' => trim($jury_decisions[$i] ?? '') ?: null,
                ':non_capitalized_credits' => trim($credits[$i] ?? '') ?: null,
                ':session' => trim($sessions[$i] ?? '') ?: null
            ]);
        }
    }

    private function save_documents($applicant_id, $prg_type_id){
        if(!isset($_FILES['documents']) || empty($_FILES['documents']['name'])){
            return;
        }

        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $target_dir = SITE_ROOT.$this->upload_dir;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }

        $sql = $this->connect->prepare("INSERT INTO tbl_applicant_documents (applicant_id, doc_id, file_path, uploaded_at) VALUES (:applicant_id, :doc_id, :file_path, NOW())");

        foreach($_FILES['documents']['name'] as $doc_id => $filename){
            if($_FILES['documents']['error'][$doc_id] !== UPLOAD_ERR_OK){
                continue;
            }

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed)){
                continue;
            }

            $new_filename = 'applicant_'.$applicant_id.'_doc_'.$doc_id.'_'.uniqid().'.'.$ext;
            $target_path = $target_dir.$new_filename;

            if(move_uploaded_file($_FILES['documents']['tmp_name'][$doc_id], $target_path)){
                $sql->execute([
                    ':applicant_id' => $applicant_id,
                    ':doc_id' => $doc_id,
                    ':file_path' => '/'.$this->upload_dir.$new_filename
                ]);
            }
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

    /**
     * A transfer student is joining an ongoing programme, so the first year is
     * not offered: only levels ranked above 1. Someone starting from the
     * beginning should use the ordinary application form instead.
     */
    public function load_levels_by_program_type(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';

        $sql = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level
                                        WHERE prg_type_id = :prg_type_id AND status = 1
                                        AND level_rank > 1
                                        ORDER BY level_rank ASC");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    /**
     * True when the level belongs to the programme and is above first year.
     * Re-checked on submit so a tampered request cannot register a first-year
     * level, or a level from a different programme.
     */
    private function is_valid_transfer_level($level_id, $prg_type_id){
        if($level_id === '' || $level_id === null){
            return false;
        }
        $sql = $this->connect->prepare("SELECT level_id FROM tbl_level
                                        WHERE level_id = :level_id
                                        AND prg_type_id = :prg_type_id
                                        AND level_rank > 1");
        $sql->execute([':level_id' => $level_id, ':prg_type_id' => $prg_type_id]);
        return $sql->fetch() !== false;
    }

    /**
     * Required documents come from tbl_formtype_document only: they are attached
     * to this form type and apply to everyone using it, whatever programme they
     * choose. The programme-based tbl_document_type is not consulted here.
     */
    public function load_documents(){
        $rows = [];

        try {
            $form_id = resolve_form_id($this->connect, $this->form_mis);
            if($form_id){
                $sql = $this->connect->prepare("SELECT doc_id, document_name, file_type, file_name,
                                                       international_required, is_required, max_size_mb
                                                FROM tbl_formtype_document
                                                WHERE form_id = :form_id AND status = 1
                                                ORDER BY document_name ASC");
                $sql->execute([':form_id' => $form_id]);
                $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log('[load_documents] no form_id resolved for mis='.$this->form_mis);
            }
        } catch(PDOException $e){
            // table missing (migration not run) - return an empty list rather
            // than a broken response, and say so in the log
            error_log('[load_documents] formtype lookup failed: '.$e->getMessage());
        }

        echo json_encode($rows);
    }
}

$transfer = new TransferStudent();
$action = $_POST['action'] ?? '';

switch($action){
    case 'complete_application':
        $transfer->complete_application();
        break;
    case 'load_faculties':
        $transfer->load_faculties();
        break;
    case 'load_program_types_by_faculty':
        $transfer->load_program_types_by_faculty();
        break;
    case 'load_departments':
        $transfer->load_departments();
        break;
    case 'load_options':
        $transfer->load_options();
        break;
    case 'load_levels_by_program_type':
        $transfer->load_levels_by_program_type();
        break;
    case 'load_documents':
        $transfer->load_documents();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
