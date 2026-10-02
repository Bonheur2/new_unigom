<?php
require_once __DIR__ . '/../../meet/session.php';
require_once __DIR__ . '/../../meet/con.php';

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class StudentInfo{
    private $connect;

    // A registration (tbl_register_program_ug) placed in the academic hierarchy:
    // university > campus > faculty > program type > department > option, with the level under the program type.
    // splz_id holds the option id. LEFT JOINs so a registration still shows when one link is missing.
    const REGISTRATION_SQL = "SELECT r.*,
                                     u.id AS university_id, u.full_name AS university_name,
                                     c.camp_full_name, f.fac_full_name, pt.prg_type_full_name,
                                     d.dept_full_name, o.opt_full_name, l.level_full_name,
                                     ac.acad_year
                              FROM tbl_register_program_ug r
                              LEFT JOIN tbl_campus c ON c.camp_id = r.camp_id
                              LEFT JOIN tbl_university u ON u.id = c.university_id
                              LEFT JOIN tbl_faculty f ON f.fac_id = r.fac_id
                              LEFT JOIN tbl_program_type pt ON pt.prg_type_id = r.prg_type
                              LEFT JOIN tbl_department d ON d.dept_id = r.dept_id
                              LEFT JOIN tbl_option o ON o.opt_id = r.splz_id
                              LEFT JOIN tbl_level l ON l.level_id = r.level_id
                              LEFT JOIN tbl_acad_cycle ac ON ac.acad_cycle_id = r.acad_cycle_id";

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function fetchAll($sql, $params = []){
        $stmt = $this->connect->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchOne($sql, $params = []){
        $stmt = $this->connect->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search(){
        $keyword = trim($_POST['keyword'] ?? '') . '%';
        echo json_encode($this->fetchAll(
            "SELECT reg_no, fname, lname FROM tbl_admission
             WHERE reg_no LIKE ? OR fname LIKE ? OR lname LIKE ? LIMIT 100",
            [$keyword, $keyword, $keyword]
        ));
    }

    public function load_info(){
        $stu = $_POST['stu'] ?? '';

        $data = $this->fetchOne("SELECT tbl_admission.*,
                                        tbl_intake.intake_month,
                                        tbl_acad_cycle.acad_year,
                                        tbl_nationality.nationality AS nat,
                                        tbl_country.cntr_name AS cname,
                                        provinces.provincename AS pname,
                                        districts.namedistrict AS dname,
                                        sectors.namesector AS sname,
                                        cells.namecell AS cellname,
                                        villages.VillageName AS vname
                                 FROM tbl_admission
                                 LEFT JOIN tbl_nationality ON tbl_admission.nationality = tbl_nationality.nat_id
                                 LEFT JOIN tbl_country ON tbl_admission.country = tbl_country.cntr_id
                                 LEFT JOIN provinces ON tbl_admission.province_id = provinces.provincecode
                                 LEFT JOIN districts ON tbl_admission.district_id = districts.districtcode
                                 LEFT JOIN sectors ON tbl_admission.sector = sectors.sectorcode
                                 LEFT JOIN cells ON tbl_admission.cell_id = cells.codecell
                                 LEFT JOIN villages ON tbl_admission.village_id = villages.CodeVillage
                                 LEFT JOIN tbl_intake ON tbl_admission.intake_id = tbl_intake.intake_id
                                 LEFT JOIN tbl_acad_cycle ON tbl_intake.acad_cycle_id = tbl_acad_cycle.acad_cycle_id
                                 WHERE tbl_admission.reg_no = ?", [$stu]);

        $programs = $this->fetchAll(self::REGISTRATION_SQL . " WHERE r.reg_no = ? ORDER BY l.level_rank DESC, r.reg_prg_id DESC", [$stu]);

        $provinces = $this->fetchAll("SELECT * FROM provinces");
        $districts = $this->fetchAll("SELECT * FROM districts WHERE provincecode = ?", [$data['province_id'] ?? '']);
        $sectors = $this->fetchAll("SELECT * FROM sectors WHERE districtcode = ?", [$data['district_id'] ?? '']);
        $cells = $this->fetchAll("SELECT * FROM cells WHERE sectorcode = ?", [$data['sector'] ?? '']);
        $villages = $this->fetchAll("SELECT * FROM villages WHERE codecell = ?", [$data['cell_id'] ?? '']);

        $passes = $this->fetchAll("SELECT * FROM tbl_principal_pass WHERE reg_no = ?", [$stu]);

        $sponsors = $this->fetchAll("SELECT tbl_register_program_ug.reg_prg_id,
                                            tbl_sponsor.spon_full_name,
                                            tbl_level.level_full_name
                                     FROM tbl_register_program_ug
                                     INNER JOIN tbl_sponsor ON tbl_register_program_ug.spon_id = tbl_sponsor.spon_id
                                     INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                     WHERE tbl_register_program_ug.reg_no = ?
                                     ORDER BY tbl_register_program_ug.level_id ASC", [$stu]);

        $previousEdu = $this->fetchAll("SELECT * FROM tbl_applicant_education WHERE stu = ? ORDER BY id DESC", [$stu]);

        $church = $this->fetchAll("SELECT ch.*, cn.cntr_name AS countryn
                                   FROM tbl_applicant_church ch
                                   INNER JOIN tbl_country cn ON ch.country = cn.cntr_id
                                   WHERE ch.stu = ?", [$stu]);

        // Order matters: index.php reads this response by position (data[0], data[1], ...).
        echo json_encode([$data, $programs, $provinces, $districts, $sectors, $cells, $villages, $passes, $sponsors, $previousEdu, $church]);
    }

    // Children of one node in the academic hierarchy, shaped as [{id, name}] for the cascading selects.
    // tbl_level hangs off the program type, beside the department.
    private function children($kind, $parent_id){
        switch($kind){
            case 'campus':
                return $this->fetchAll("SELECT camp_id AS id, camp_full_name AS name FROM tbl_campus
                                        WHERE university_id = ? ORDER BY camp_full_name", [$parent_id]);
            case 'faculty':
                return $this->fetchAll("SELECT fac_id AS id, fac_full_name AS name FROM tbl_faculty
                                        WHERE campus_id = ? AND status = 1 ORDER BY fac_full_name", [$parent_id]);
            case 'program_type':
                return $this->fetchAll("SELECT prg_type_id AS id, prg_type_full_name AS name FROM tbl_program_type
                                        WHERE fac_id = ? AND status = 1 ORDER BY prg_type_full_name", [$parent_id]);
            case 'department':
                return $this->fetchAll("SELECT dept_id AS id, dept_full_name AS name FROM tbl_department
                                        WHERE prg_type = ? AND status = 1 ORDER BY dept_full_name", [$parent_id]);
            case 'option':
                return $this->fetchAll("SELECT opt_id AS id, opt_full_name AS name FROM tbl_option
                                        WHERE dept_id = ? AND status = 1 ORDER BY opt_full_name", [$parent_id]);
            case 'level':
                return $this->fetchAll("SELECT level_id AS id, level_full_name AS name FROM tbl_level
                                        WHERE prg_type_id = ? AND status = 1 ORDER BY level_rank", [$parent_id]);
        }
        return [];
    }

    public function load_academic_info(){
        $record = $this->fetchOne(self::REGISTRATION_SQL . " WHERE r.reg_prg_id = ?", [$_POST['id'] ?? '']);
        if(!$record){
            echo json_encode(['status' => 404, 'message' => 'Inscription introuvable']);
            return;
        }

        echo json_encode([
            'status' => 200,
            'record' => $record,
            'lists' => [
                'campus' => $this->children('campus', $record['university_id']),
                'faculty' => $this->children('faculty', $record['camp_id']),
                'program_type' => $this->children('program_type', $record['fac_id']),
                'department' => $this->children('department', $record['prg_type']),
                'option' => $this->children('option', $record['dept_id']),
                'level' => $this->children('level', $record['prg_type']),
            ],
        ]);
    }

    public function load_children(){
        echo json_encode($this->children($_POST['kind'] ?? '', $_POST['parent'] ?? ''));
    }

    public function update_academic(){
        $id = (int)($_POST['reg_prg_id'] ?? 0);
        $camp_id = (int)($_POST['camp_id'] ?? 0);
        $fac_id = (int)($_POST['fac_id'] ?? 0);
        $prg_type = (int)($_POST['prg_type'] ?? 0);
        $dept_id = (int)($_POST['dept_id'] ?? 0);
        $opt_id = (int)($_POST['opt_id'] ?? 0);
        $level_id = (int)($_POST['level_id'] ?? 0);
        $reg_active = (int)($_POST['reg_active'] ?? 1);

        if(!$id || !$camp_id || !$fac_id || !$prg_type || !$dept_id || !$opt_id || !$level_id){
            echo json_encode(['status' => 401, 'message' => 'Veuillez remplir tous les champs du parcours']);
            return;
        }

        // The whole chain must hold together: option -> department -> program type -> faculty -> campus,
        // and the level must belong to the same program type.
        $chain = $this->fetchOne("SELECT o.opt_id
                                  FROM tbl_option o
                                  INNER JOIN tbl_department d ON d.dept_id = o.dept_id
                                  INNER JOIN tbl_program_type pt ON pt.prg_type_id = d.prg_type
                                  INNER JOIN tbl_faculty f ON f.fac_id = pt.fac_id
                                  INNER JOIN tbl_level l ON l.prg_type_id = pt.prg_type_id
                                  WHERE o.opt_id = ? AND d.dept_id = ? AND pt.prg_type_id = ?
                                    AND f.fac_id = ? AND f.campus_id = ? AND l.level_id = ?",
                                  [$opt_id, $dept_id, $prg_type, $fac_id, $camp_id, $level_id]);
        if(!$chain){
            echo json_encode(['status' => 401, 'message' => 'Le parcours choisi est incohérent, veuillez vérifier les sélections']);
            return;
        }

        // splz_id holds the option; prg_id mirrors prg_type, as admission writes it.
        $stmt = $this->connect->prepare("UPDATE tbl_register_program_ug
                                         SET camp_id = ?, fac_id = ?, prg_type = ?, prg_id = ?, dept_id = ?,
                                             splz_id = ?, level_id = ?, reg_active = ?
                                         WHERE reg_prg_id = ?");
        $stmt->execute([$camp_id, $fac_id, $prg_type, $prg_type, $dept_id, $opt_id, $level_id, $reg_active, $id]);
        echo json_encode(['status' => 200, 'message' => 'Parcours académique mis à jour !']);
    }

    public function load_pass_info(){
        echo json_encode($this->fetchOne("SELECT * FROM tbl_principal_pass WHERE id = ?", [$_POST['course_id'] ?? '']));
    }

    public function load_sponsor_info(){
        echo json_encode($this->fetchOne("SELECT * FROM tbl_register_program_ug WHERE reg_prg_id = ?", [$_POST['id'] ?? '']));
    }

    public function update_personal(){
        $fname = trim($_POST['fname'] ?? '');
        $lname = trim($_POST['lname'] ?? '');
        $nid = trim($_POST['nid'] ?? '');
        $father_names = trim($_POST['father_names'] ?? '');
        $mother_names = trim($_POST['mother_names'] ?? '');

        if($fname == '' || $lname == '' || $nid == '' || $father_names == '' || $mother_names == ''){
            echo json_encode(['status' => 401, 'message' => 'Veuillez remplir correctement le formulaire']);
            return;
        }

        $stmt = $this->connect->prepare("UPDATE tbl_admission
                                         SET fname = ?, lname = ?, marital_status = ?, dob = ?, nationality = ?,
                                             ID = ?, gender = ?, father_names = ?, mother_names = ?
                                         WHERE reg_no = ?");
        $stmt->execute([
            $fname, $lname, $_POST['marital_status'] ?? '', $_POST['dob'] ?? '', $_POST['nationality'] ?? '',
            $nid, $_POST['gender'] ?? '', $father_names, $mother_names, $_POST['stu'] ?? ''
        ]);
        echo json_encode(['status' => 200, 'message' => 'Profil mis à jour !']);
    }

    public function update_contact(){
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if($phone == '' || $email == ''){
            echo json_encode(['status' => 401, 'message' => 'Veuillez remplir correctement le formulaire']);
            return;
        }

        $stmt = $this->connect->prepare("UPDATE tbl_admission
                                         SET email = ?, phone = ?, parent_phone = ?, ref_phone = ?,
                                             kin_name = ?, kin_relation = ?, kin_address = ?, kin_email = ?, kin_tel = ?
                                         WHERE reg_no = ?");
        $stmt->execute([
            $email, $phone, trim($_POST['parent_phone'] ?? ''), trim($_POST['ref_phone'] ?? ''),
            $_POST['kin_name'] ?? '', $_POST['kin_relation'] ?? '', $_POST['kin_address'] ?? '',
            trim($_POST['kin_email'] ?? ''), trim($_POST['kin_tel'] ?? ''), $_POST['stuc'] ?? ''
        ]);
        echo json_encode(['status' => 200, 'message' => 'Profil mis à jour !']);
    }

    public function update_address(){
        $stmt = $this->connect->prepare("UPDATE tbl_admission
                                         SET country = ?, province_id = ?, district_id = ?, street = ?
                                         WHERE reg_no = ?");
        $stmt->execute([
            $_POST['country'] ?? '', $_POST['province_id'] ?? '', $_POST['district_id'] ?? '',
            $_POST['street'] ?? '', $_POST['stua'] ?? ''
        ]);
        echo json_encode(['status' => 200, 'message' => 'Profil mis à jour !']);
    }
}

if(empty($_SESSION['access'])){
    echo json_encode(['status' => 401, 'message' => 'Session expirée, veuillez vous reconnecter']);
    exit;
}

$student = new StudentInfo();
$action = $_POST['action'] ?? '';

try{
    switch($action){
        case 'search':             $student->search(); break;
        case 'load_info':          $student->load_info(); break;
        case 'load_academic_info': $student->load_academic_info(); break;
        case 'load_children':      $student->load_children(); break;
        case 'update_academic':    $student->update_academic(); break;
        case 'load_pass_info':     $student->load_pass_info(); break;
        case 'load_sponsor_info':  $student->load_sponsor_info(); break;
        case 'update_personal':    $student->update_personal(); break;
        case 'update_contact':     $student->update_contact(); break;
        case 'update_address':     $student->update_address(); break;
        default:
            echo json_encode(['status' => 400, 'message' => 'Action inconnue']);
    }
}catch(PDOException $e){
    echo json_encode(['status' => 500, 'message' => 'Impossible de traiter la demande']);
}
