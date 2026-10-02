<?php
// Controller for the re-integration form (integration/index.php).
// For an applicant who interrupted their studies and wants to resume at UNIGOM.
// Updates their identity on tbl_applicants and records the re-integration
// details in tbl_integration_requests for staff review.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
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

class Integration{
    private $connect;
    private $form_mis = 'integration';
    private $upload_dir = 'uploads/applicant_documents/';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
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

    private function has_pending_request($application_code){
        $sql = $this->connect->prepare("SELECT request_id FROM tbl_integration_requests WHERE application_code = :application_code AND status = 'pending' LIMIT 1");
        $sql->execute([':application_code' => $application_code]);
        return $sql->fetch() !== false;
    }

    public function submit_request(){
        $identification = $_SESSION['identification'] ?? '';

        if($identification === ''){
            echo json_encode(['status' => 401, 'message' => 'You must be logged in to request re-integration']);
            return;
        }

        $applicant = $this->get_applicant_by_identification($identification);
        if(!$applicant){
            echo json_encode(['status' => 401, 'message' => 'Applicant record not found']);
            return;
        }

        if($this->has_pending_request($applicant['application_code'])){
            echo json_encode(['status' => 401, 'message' => 'You already have a pending re-integration request. Please wait for it to be reviewed.']);
            return;
        }

        // --- identity ---
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

        // --- re-integration ---
        $interruption_year = trim($_POST['interruption_year'] ?? '');
        $requested_fac_id = $_POST['fac_id_1'] ?? '';
        $requested_prg_type_id = $_POST['prg_type_id_1'] ?? '';
        $requested_dept_id = $_POST['dept_choice_1'] ?? '';
        $requested_opt_id = $_POST['opt_choice_1'] ?? '';
        $requested_level_id = $_POST['level_id'] ?? '';
        $last_attended = trim($_POST['last_attended'] ?? '');
        $last_result_percentage = trim($_POST['last_result_percentage'] ?? '') ?: null;
        $last_result_session = trim($_POST['last_result_session'] ?? '');
        $suspension_reason = trim($_POST['suspension_reason'] ?? '');
        $documents_left = trim($_POST['documents_left'] ?? '');
        $transcript_attached = $_POST['transcript_attached'] ?? '';
        $obligations_library = trim($_POST['obligations_library'] ?? '');
        $obligations_finance = trim($_POST['obligations_finance'] ?? '');
        $activities_during_suspension = trim($_POST['activities_during_suspension'] ?? '');
        $resume_reason = trim($_POST['resume_reason'] ?? '');
        $health_status = trim($_POST['health_status'] ?? '');
        $tuition_ability = trim($_POST['tuition_ability'] ?? '');

        if($dob === '' || $father_name === '' || $mother_name === ''){
            echo json_encode(['status' => 401, 'message' => 'Please fill all required fields']);
            return;
        }

        if($requested_fac_id === '' || $requested_prg_type_id === '' || $requested_dept_id === '' || $requested_level_id === ''){
            echo json_encode(['status' => 401, 'message' => 'Please complete the faculty, programme, department and class you are applying for']);
            return;
        }

        if($interruption_year === '' || $last_attended === '' || $suspension_reason === '' || $resume_reason === ''){
            echo json_encode(['status' => 401, 'message' => 'Please complete the re-integration details']);
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
                                            prg_type_id = :prg_type_id,
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
                ':prg_type_id' => $requested_prg_type_id,
                ':form_id' => resolve_form_id($this->connect, $this->form_mis),
                ':application_period_id' => $this->get_active_application_period(),
                ':id' => $applicant['applicant_id']
            ]);

            $ins = $this->connect->prepare("INSERT INTO tbl_integration_requests
                                            (applicant_id, application_code, interruption_year,
                                             requested_fac_id, requested_prg_type_id, requested_dept_id,
                                             requested_opt_id, requested_level_id,
                                             last_attended, last_result_percentage, last_result_session,
                                             suspension_reason, documents_left, transcript_attached,
                                             obligations_library, obligations_finance,
                                             activities_during_suspension, resume_reason,
                                             health_status, tuition_ability, status)
                                            VALUES
                                            (:applicant_id, :application_code, :interruption_year,
                                             :requested_fac_id, :requested_prg_type_id, :requested_dept_id,
                                             :requested_opt_id, :requested_level_id,
                                             :last_attended, :last_result_percentage, :last_result_session,
                                             :suspension_reason, :documents_left, :transcript_attached,
                                             :obligations_library, :obligations_finance,
                                             :activities_during_suspension, :resume_reason,
                                             :health_status, :tuition_ability, 'pending')");
            $ins->execute([
                ':applicant_id' => $applicant['applicant_id'],
                ':application_code' => $applicant['application_code'],
                ':interruption_year' => $interruption_year,
                ':requested_fac_id' => $requested_fac_id,
                ':requested_prg_type_id' => $requested_prg_type_id,
                ':requested_dept_id' => $requested_dept_id,
                ':requested_opt_id' => $requested_opt_id !== '' ? $requested_opt_id : null,
                ':requested_level_id' => $requested_level_id !== '' ? $requested_level_id : null,
                ':last_attended' => $last_attended,
                ':last_result_percentage' => $last_result_percentage,
                ':last_result_session' => $last_result_session,
                ':suspension_reason' => $suspension_reason,
                ':documents_left' => $documents_left,
                ':transcript_attached' => $transcript_attached !== '' ? $transcript_attached : null,
                ':obligations_library' => $obligations_library,
                ':obligations_finance' => $obligations_finance,
                ':activities_during_suspension' => $activities_during_suspension,
                ':resume_reason' => $resume_reason,
                ':health_status' => $health_status,
                ':tuition_ability' => $tuition_ability
            ]);

            $this->save_documents($applicant['applicant_id'], $requested_prg_type_id);

            // A failed email must never fail the submission - the helper logs and
            // returns false rather than throwing.
            send_integration_confirmation(
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
                'message' => 'Your re-integration request has been submitted successfully and is pending review.'
            ]);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error submitting request: '.$e->getMessage()]);
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

        $sql = $this->connect->prepare("INSERT INTO tbl_applicant_documents (applicant_id, doc_id, file_path, uploaded_at)
                                        VALUES (:applicant_id, :doc_id, :file_path, NOW())");

        foreach($_FILES['documents']['name'] as $doc_id => $name){
            if($name === '' || $_FILES['documents']['error'][$doc_id] !== UPLOAD_ERR_OK){
                continue;
            }

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed, true)){
                continue;
            }

            $filename = 'doc_'.$applicant_id.'_'.$doc_id.'_'.uniqid().'.'.$ext;
            $target = $target_dir.$filename;

            if(move_uploaded_file($_FILES['documents']['tmp_name'][$doc_id], $target)){
                $sql->execute([
                    ':applicant_id' => $applicant_id,
                    ':doc_id' => $doc_id,
                    ':file_path' => '/'.$this->upload_dir.$filename
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

    public function load_levels_by_program_type(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';

        $sql = $this->connect->prepare("SELECT level_id, level_full_name FROM tbl_level WHERE prg_type_id = :prg_type_id AND status = 1 ORDER BY level_rank ASC");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }

    public function load_documents(){
        $prg_type_id = $_POST['prg_type_id'] ?? '';

        $sql = $this->connect->prepare("SELECT doc_id, document_name, file_type, file_name, international_required
                                        FROM tbl_document_type
                                        WHERE prg_type_id = :prg_type_id
                                        AND status = 1
                                        ORDER BY document_name ASC");
        $sql->execute([':prg_type_id' => $prg_type_id]);
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($rows);
    }
}

$integration = new Integration();
$action = $_POST['action'] ?? '';

switch($action){
    case 'submit_request':
        $integration->submit_request();
        break;
    case 'load_faculties':
        $integration->load_faculties();
        break;
    case 'load_program_types_by_faculty':
        $integration->load_program_types_by_faculty();
        break;
    case 'load_departments':
        $integration->load_departments();
        break;
    case 'load_options':
        $integration->load_options();
        break;
    case 'load_levels_by_program_type':
        $integration->load_levels_by_program_type();
        break;
    case 'load_documents':
        $integration->load_documents();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
