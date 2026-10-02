<?php
include ('../meet/con.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
$connection=$conn;

class Program{
    private $connect;
    public function __construct() {
        global $connection;
        $this->connect = $connection;
    }

    public function getFaculties($prg_type) {
        $sql = $this->connect->prepare("SELECT fac_id, fac_full_name, fac_short_name 
                                        FROM tbl_faculty 
                                        WHERE prg_type = ? AND status = 1 
                                        ORDER BY fac_full_name ASC");
        $sql->execute([$prg_type]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartments($fac_id, $prg_type) {
        $sql = $this->connect->prepare("SELECT dept_id, dept_full_name, dept_short_name 
                                        FROM tbl_department 
                                        WHERE fac_id = ? AND prg_type = ? AND status = 1 
                                        ORDER BY dept_full_name ASC");
        $sql->execute([$fac_id, $prg_type]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpecializations($dept_id, $fac_id, $prg_type) {
        $sql = $this->connect->prepare("SELECT splz_id, splz_full_name, splz_short_name 
                                        FROM tbl_specialization 
                                        WHERE dept_id = ? AND fac_id = ? AND prg_type = ? AND status = 1 
                                        ORDER BY splz_full_name ASC");
        $sql->execute([$dept_id, $fac_id, $prg_type]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApplicants($prg_type) {
        $sql = $this->connect->prepare("SELECT
                            distinct(tbl_admittedPRG.Stu_code) as code,
                            tbl_applicants.fname,
                            tbl_applicants.lname,
                            tbl_applicants.createdAt,
                            tbl_campus.camp_full_name,
                            tbl_program_type.prg_type_full_name,
                            tbl_faculty.fac_full_name,
                            tbl_department.dept_full_name,
                            tbl_specialization.splz_full_name,
                            tbl_program_mode.prg_mode_full_name,
                            tbl_level.level_full_name
                        FROM tbl_applicants
                        INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                        INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                        INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                        INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                        INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                        INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                        INNER JOIN tbl_program_mode ON tbl_admittedPRG.mode = tbl_program_mode.prg_mode_id
                        LEFT JOIN tbl_level ON tbl_admittedPRG.level = tbl_level.level_id
                        WHERE tbl_admittedPRG.prg_type = ?
                        GROUP BY tbl_admittedPRG.Stu_code");
        $sql->execute([$prg_type]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function forwardApplication($aprg_id) {
        $sql = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 2 WHERE Aprg_id = ? AND sts = 1");
        $sql->execute([$aprg_id]);
        return $sql->rowCount();
    }

    public function communicateApplication($aprg_id, $message) {
        // Update status to 4 (communicated/rejected)
        $sql = $this->connect->prepare("UPDATE tbl_admittedPRG SET sts = 4 WHERE Aprg_id = ?");
        $sql->execute([$aprg_id]);

        // Get student code for this application
        $sql2 = $this->connect->prepare("SELECT Stu_code FROM tbl_admittedPRG WHERE Aprg_id = ?");
        $sql2->execute([$aprg_id]);
        $row = $sql2->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Insert communication message
            $sql3 = $this->connect->prepare("INSERT INTO tbl_communications (stu_code, aprg_id, message, created_at) VALUES (?, ?, ?, NOW())");
            $sql3->execute([$row['Stu_code'], $aprg_id, $message]);
        }

        return $sql->rowCount();
    }
}

$program = new Program();
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch($action){
    case 'load-faculties':
        $prg_type = $_POST['prg_type'];
        $data = $program->getFaculties($prg_type);
        echo json_encode($data);
        break;

    case 'load-departments':
        $fac_id = $_POST['fac_id'];
        $prg_type = $_POST['prg_type'];
        $data = $program->getDepartments($fac_id, $prg_type);
        echo json_encode($data);
        break;

    case 'load-specs':
        $dept_id = $_POST['dept_id'];
        $fac_id = $_POST['fac_id'];
        $prg_type = $_POST['prg_type'];
        $data = $program->getSpecializations($dept_id, $fac_id, $prg_type);
        echo json_encode($data);
        break;

    case 'load-applicants':
        $prg_type = $_POST['prg_type'];
        $data = $program->getApplicants($prg_type);
        echo json_encode($data);
        break;

    case 'forward':
        $aprg_id = $_POST['ad_m_i'];
        $result = $program->forwardApplication($aprg_id);
        if ($result > 0) {
            echo json_encode(['status' => 200, 'message' => 'Application forwarded successfully!']);
        } else {
            echo json_encode(['status' => 400, 'message' => 'Failed to forward application or already forwarded.']);
        }
        break;

    case 'communicate':
        $aprg_id = $_POST['ad_m_id'];
        $message = $_POST['message'];
        $result = $program->communicateApplication($aprg_id, $message);
        if ($result > 0) {
            echo json_encode(['status' => 200, 'message' => 'Message sent successfully!']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Failed to send message.']);
        }
        break;

    default:
        echo json_encode(['status' => 400, 'message' => 'Invalid action.']);
        break;
}
?>