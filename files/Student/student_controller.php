<?php
    include ('../../meet/con.php');
    

    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $connection=$conn;
    class Student{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    		$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}
        function search(){
        	$input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT reg_no,fname,lname FROM tbl_admission WHERE reg_no like '".$input."%' OR fname like '".$input."%' OR lname like '".$input."%' limit 100");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
function search_app(){
    
            $input=$_POST['keyword'];
            $stmt = $this->connect->prepare("SELECT code, fname, lname FROM tbl_applicants WHERE code like '".$input."%' OR fname like '".$input."%' OR lname like '".$input."%' limit 100");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;

}

        function load_student_info(){
        	$stu=$_POST['stu'];
            $stmt = $this->connect->prepare("SELECT tbl_admission.*,
                                                     tbl_intake.intake_month,
                                                     tbl_acad_cycle.acad_year,
                                                     tbl_nationality.nationality as nat,
                                                     tbl_country.cntr_name as cname,
                                                     provinces.provincename as pname,
                                                     districts.namedistrict as dname,
                                                     sectors.namesector as sname,
                                                     cells.namecell as cellname,
                                                     villages.VillageName as vname
                                                     FROM tbl_admission
                                                     LEFT JOIN tbl_nationality ON
                                                     tbl_admission.nationality=tbl_nationality.nat_id
                                                     LEFT JOIN tbl_country ON
                                                     tbl_admission.country=tbl_country.cntr_id
                                                     LEFT JOIN provinces ON
                                                     tbl_admission.province_id=provinces.provincecode
                                                     LEFT JOIN districts ON
                                                     tbl_admission.district_id=districts.districtcode
                                                     LEFT JOIN sectors ON
                                                     tbl_admission.sector=sectors.sectorcode
                                                     LEFT JOIN cells ON
                                                     tbl_admission.cell_id=cells.codecell
                                                     LEFT JOIN villages ON
                                                     tbl_admission.village_id=villages.CodeVillage
                                                     LEFT JOIN tbl_intake ON
                                                     tbl_admission.intake_id=tbl_intake.intake_id
                                                     LEFT JOIN tbl_acad_cycle ON
                                                     tbl_intake.acad_cycle_id=tbl_acad_cycle.acad_cycle_id
                                                     WHERE tbl_admission.reg_no='".$stu."'");
            $stmt->execute();
            $data=$stmt->fetch();
            
            $sql=$this->connect->prepare("SELECT
                                    tbl_register_program_ug.*,
                                    tbl_program_type.prg_type_full_name,
                                    tbl_faculty.fac_full_name,
                                    tbl_department.dept_full_name,
                                    tbl_specialization.splz_full_name,
                                    tbl_level.level_full_name,
                                    tbl_program_mode.prg_mode_full_name,
                                    tbl_acad_cycle.acad_year,
                                    tbl_status.status_full_name
                                        FROM tbl_register_program_ug
                                    INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                    INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                    INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                    INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
                                    INNER JOIN tbl_acad_cycle ON tbl_register_program_ug.acad_cycle_id = tbl_acad_cycle.acad_cycle_id
                                    INNER JOIN tbl_status ON tbl_register_program_ug.reg_active = tbl_status.status_id
                                        WHERE
                                    tbl_register_program_ug.reg_no = '".$stu."' ORDER BY tbl_register_program_ug.level_id DESC");
            $sql->execute();
            $data2=$sql->fetchAll();
            
            //districts
            $stmt0 = $this->connect->prepare("SELECT * FROM provinces");
            $stmt0->execute();
            $provinces = $stmt0->fetchAll();
            //districts
            $stmt1 = $this->connect->prepare("SELECT * FROM districts WHERE provincecode='".$data['province_id']."'");
            $stmt1->execute();
            $districts = $stmt1->fetchAll();
            //sectors
            $stmt2 = $this->connect->prepare("SELECT * FROM sectors WHERE districtcode='".$data['district_id']."'");
            $stmt2->execute();
            $sectors = $stmt2->fetchAll();
            //cells
            $stmt3 = $this->connect->prepare("SELECT * FROM cells WHERE sectorcode='".$data['sector']."'");
            $stmt3->execute();
            $cells = $stmt3->fetchAll();
            //villages
            $stmt4 = $this->connect->prepare("SELECT * FROM villages WHERE codecell='".$data['cell_id']."'");
            $stmt4->execute();
            $villages = $stmt4->fetchAll();
            
            $stmt5 = $this->connect->prepare("SELECT * FROM tbl_principal_pass WHERE reg_no = '".$stu."'");
            $stmt5->execute();
            $passes = $stmt5->fetchAll();
            
            $stmt6 = $this->connect->prepare("SELECT
                                    tbl_register_program_ug.reg_prg_id,
                                    tbl_sponsor.spon_full_name,
                                    tbl_level.level_full_name
                                        FROM tbl_register_program_ug
                                    INNER JOIN tbl_sponsor ON tbl_register_program_ug.spon_id = tbl_sponsor.spon_id
                                    INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                        WHERE
                                    tbl_register_program_ug.reg_no = '".$stu."' ORDER BY tbl_register_program_ug.level_id ASC");
            $stmt6->execute();
            $sponsor = $stmt6->fetchAll(); 
            
            $stmt7 = $this->connect->prepare("SELECT * FROM tbl_applicant_education WHERE stu = '".$stu."' ORDER BY id DESC");
            $stmt7->execute();
            $previousEdu = $stmt7->fetchAll();
            
            $stmt8 = $this->connect->prepare("SELECT ch.*, cn.cntr_name as countryn FROM tbl_applicant_church ch INNER JOIN tbl_country cn ON ch.country=cn.cntr_id WHERE ch.stu = '".$stu."'");
            $stmt8->execute();
            $churchInfo = $stmt8->fetchAll();
            
            
            $info=array();
            array_push($info,$data,$data2,$provinces,$districts,$sectors,$cells,$villages, $passes, $sponsor, $previousEdu, $churchInfo);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_levels(){
        	$stu=$_POST['stu'];
            $stmt = $this->connect->prepare("SELECT DISTINCT(r.level_id), l.level_full_name
                                                    FROM tbl_register_program_ug r
                                                     INNER JOIN tbl_level l ON
                                                     r.level_id = l.level_id
                                                    WHERE r.reg_no='".$stu."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_academic_info(){
        	$record=$_POST['id'];
            $stmt1 = $this->connect->prepare("SELECT * FROM tbl_register_program_ug WHERE reg_prg_id = '".$record."'");
            $stmt1->execute();
            $data1 = $stmt1->fetch();
            
            $stmt2 = $this->connect->prepare("SELECT * FROM tbl_faculty WHERE prg_type='".$data1['prg_type']."'");
            $stmt2->execute();
            $data2 = $stmt2->fetchAll();
            
            $stmt3 = $this->connect->prepare("SELECT * FROM tbl_department WHERE fac_id='".$data1['fac_id']."'");
            $stmt3->execute();
            $data3 = $stmt3->fetchAll();

            $stmt4 = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE dept_id='".$data1['dept_id']."'");
            $stmt4->execute();
            $data4 = $stmt4->fetchAll();
            
            $stmt5 = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$data1['prg_type']."'");
            $stmt5->execute();
            $data5 = $stmt5->fetchAll();
            
            $info = array();
            array_push($info, $data1, $data2, $data3, $data4, $data5);
            $jsonData = json_encode($info);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_pass_info(){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_principal_pass WHERE id='".$_POST['course_id']."'");
            $stmt->execute();
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function load_sponsor_info(){
            $stmt = $this->connect->prepare("SELECT * FROM tbl_register_program_ug WHERE reg_prg_id='".$_POST['id']."'");
            $stmt->execute();
            $data = $stmt->fetch();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        function update_profile_personal(){
            $stu=$_POST['stu'];
            $fname=trim($_POST['fname']," ");
            $lname=trim($_POST['lname']," ");
            $nationality=$_POST['nationality'];
            $nid=trim($_POST['nid']," ");
            $father_names=trim($_POST['father_names']," ");
            $mother_names=trim($_POST['mother_names']," ");
            $gender=$_POST['gender'];
            $dob=$_POST['dob'];
            $marital_status=$_POST['marital_status'];
            
            if($fname=="" || $lname=="" || $nid=="" || $father_names=="" || $mother_names==""){
                $data = array("status"=>"401","message" => "Fill the form correctly");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $stmtu = $this->connect->prepare("UPDATE tbl_admission SET fname='".$fname."',lname='".$lname."',marital_status='".$marital_status."',dob='".$dob."',nationality='".$nationality."',ID='".$nid."',gender='".$gender."',father_names='".$father_names."',mother_names='".$mother_names."' WHERE reg_no='".$stu."'");
                if($stmtu->execute()){
                    $data = array("status"=>"200","message" => "Profile is updated!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }else{
                    $data = array("status"=>"500","message" => "Failed to update data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
            }
        }
        function update_profile_contact(){
            $stu=$_POST['stuc'];
            $phone=trim($_POST['phone']," ");
            $email=trim($_POST['email']," ");
            $parent_phone=trim($_POST['parent_phone']," ");
            $ref_phone=trim($_POST['ref_phone']," ");
            
            $kin_name = $_POST['kin_name'];
            $kin_relation = $_POST['kin_relation'];
            $kin_address = $_POST['kin_address'];
            $kin_email = trim($_POST['kin_email']," ");
            $kin_tel = trim($_POST['kin_tel']," ");
            
            if($phone=="" || $email==""){
                $data = array("status"=>"401","message" => "Fill the form correctly");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $stmtu = $this->connect->prepare("UPDATE tbl_admission SET email='".$email."',phone='".$phone."',parent_phone='".$parent_phone."',ref_phone='".$ref_phone."', kin_name='".$kin_name."', kin_relation='".$kin_relation."', kin_address='".$kin_address."', kin_email='".$kin_email."', kin_tel='".$kin_tel."' WHERE reg_no='".$stu."'");
                if($stmtu->execute()){
                    $data = array("status"=>"200","message" => "Profile is updated!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }else{
                    $data = array("status"=>"500","message" => "Failed to update data!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;
                }
            }
        }
        function update_profile_address(){
            $stu=$_POST['stua'];
            $country=$_POST['country'];
            $province_id=$_POST['province_id'];
            $district_id=$_POST['district_id'];
            $street=$_POST['street'];
            $stmtu = $this->connect->prepare("UPDATE tbl_admission SET country='".$country."',province_id='".$province_id."',district_id='".$district_id."',street='".$street."' WHERE reg_no='".$stu."'");
            if($stmtu->execute()){
                $data = array("status"=>"200","message" => "Profile is updated!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }else{
                $data = array("status"=>"500","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }
        }
        
        function load_invoices(){
        	$reg_no=$_POST['stu'];
        	$intake=$_POST['intake'];
            $stmt = $this->connect->prepare("SELECT DISTINCT(i.fee_id), f.name
                                                FROM tbl_invoice i
                                                    INNER JOIN fee_category f ON
                                                        i.fee_id=f.id
                                                    WHERE i.reg_no='".$reg_no."' AND i.intake_id='".$intake."'");
            $stmt->execute();
            $data=$stmt->fetchAll();
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
        
        
        function load_student_info_payment(){
        	$stu=$_POST['stu'];
            $sql=$this->connect->prepare("SELECT
                                                r.*,
                                                s.splz_full_name,
                                                l.level_full_name,
                                                ac.acad_year,
                                                COALESCE(p.payment, 0) as payment,
                                                COALESCE(inv.invoice, 0) as invoice,
                                                COALESCE(sp_inv.special, 0) as special
                                            FROM tbl_register_program_ug r
                                                INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                                INNER JOIN tbl_level l ON r.level_id = l.level_id
                                                INNER JOIN tbl_acad_cycle ac ON r.acad_cycle_id = ac.acad_cycle_id

                                                LEFT JOIN (
                                                    SELECT reg_no, acad_cycle_id, SUM(amount) as payment
                                                    FROM payment_trial
                                                    WHERE status = 1
                                                    GROUP BY reg_no, acad_cycle_id
                                                ) p ON r.reg_no = p.reg_no AND r.acad_cycle_id = p.acad_cycle_id
                                                LEFT JOIN (
                                                    SELECT reg_no, acad_cycle_id, SUM(balance) as invoice
                                                    FROM tbl_invoice
                                                    WHERE special_fee_id IS NULL
                                                    GROUP BY reg_no, acad_cycle_id
                                                ) inv ON r.reg_no = inv.reg_no AND r.acad_cycle_id = inv.acad_cycle_id
                                                LEFT JOIN (
                                                    SELECT reg_no, acad_cycle_id, SUM(balance) as special
                                                    FROM tbl_invoice
                                                    WHERE special_fee_id IS NOT NULL
                                                    GROUP BY reg_no, acad_cycle_id
                                                ) sp_inv ON r.reg_no = sp_inv.reg_no AND r.acad_cycle_id = sp_inv.acad_cycle_id
                                            WHERE
                                                r.reg_no = '".$stu."'
                                            ORDER BY r.reg_prg_id DESC"
                                            );
            $sql->execute();
            $data=$sql->fetchAll();
            

            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
        function load_application_info() {
    try {
        $stu = $_POST['stu']; // Capture the student registration number

        // Check if the input is provided
        if (empty($stu)) {
            throw new Exception("Student registration number is required.");
        }

        // Prepare SQL query to fetch student details, payment, and invoice data
        $sql = $this->connect->prepare("
            SELECT
    r.*,
    COALESCE(p.payment, 0) AS payment,
    COALESCE(inv.invoice, 0) AS invoice
FROM tbl_applicants r
LEFT JOIN (
    SELECT reg_no, SUM(amount) AS payment
    FROM payment_trial
    WHERE status = 1
    GROUP BY reg_no
) p ON r.code = p.reg_no
LEFT JOIN (
    SELECT reg_no, SUM(balance) AS invoice
    FROM tbl_invoice
    GROUP BY reg_no
) inv ON r.code = inv.reg_no
WHERE r.code = :stu
ORDER BY r.applicant_id DESC

        ");

        // Bind the parameter securely to avoid SQL injection
        $sql->bindParam(':stu', $stu, PDO::PARAM_STR);
        $sql->execute();

        $data = $sql->fetchAll();

        // Set JSON response header and output the data
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        // Handle and return errors in JSON format
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function save_on_semester() {
    $sem_id = $_POST['sem_id'];
    $reg_no = $_POST['reg_no'];

    // Select student details
    $select_stud = "SELECT * FROM tbl_register_program_ug WHERE reg_no = :reg_no AND reg_active = 1";
    $cselect_stud = $this->connect->prepare($select_stud);
    $cselect_stud->execute(['reg_no' => $reg_no]);
    $row_ccselect_stud = $cselect_stud->fetch(PDO::FETCH_ASSOC);

    $acad_cycle_id = $row_ccselect_stud['acad_cycle_id'];
    $prg_type = $row_ccselect_stud['prg_type'];
    $splz_id = $row_ccselect_stud['splz_id'];
    $fac_id = $row_ccselect_stud['fac_id'];
    $dept_id = $row_ccselect_stud['dept_id'];
    $level_id = $row_ccselect_stud['level_id'];
    $prg_mode_id = $row_ccselect_stud['prg_mode_id'];
    $campus = $row_ccselect_stud['camp_id'];
    $date_done = date('Y-m-d');

    // Check if already registered
    $select_before = "SELECT * FROM tbl_student_semester WHERE reg_no = :reg_no AND acad_cycle_id = :acad_cycle_id AND prg_type = :prg_type AND splz_id = :splz_id AND fac_id = :fac_id AND dept_id = :dept_id AND level_id = :level_id AND prg_mode_id = :prg_mode_id AND campus = :campus AND sem_id = :sem_id";
    $cselect_before = $this->connect->prepare($select_before);
    $cselect_before->execute([
        'reg_no' => $reg_no,
        'acad_cycle_id' => $acad_cycle_id,
        'prg_type' => $prg_type,
        'splz_id' => $splz_id,
        'fac_id' => $fac_id,
        'dept_id' => $dept_id,
        'level_id' => $level_id,
        'prg_mode_id' => $prg_mode_id,
        'campus' => $campus,
        'sem_id' => $sem_id
    ]);
    $row_cselect_before = $cselect_before->fetch(PDO::FETCH_ASSOC);

    if ($row_cselect_before) {
        $data = array("status" => "500", "message" => "You have Already Registered On this Semester");
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        // Insert new semester registration
        $insert_semester = "INSERT INTO tbl_student_semester (reg_no, acad_cycle_id, prg_type, splz_id, fac_id, dept_id, level_id, prg_mode_id, campus, sem_id, date_done) VALUES (:reg_no, :acad_cycle_id, :prg_type, :splz_id, :fac_id, :dept_id, :level_id, :prg_mode_id, :campus, :sem_id, :date_done)";
        $cinsert_semester = $this->connect->prepare($insert_semester);
        $cinsert_semester->execute([
            'reg_no' => $reg_no,
            'acad_cycle_id' => $acad_cycle_id,
            'prg_type' => $prg_type,
            'splz_id' => $splz_id,
            'fac_id' => $fac_id,
            'dept_id' => $dept_id,
            'level_id' => $level_id,
            'prg_mode_id' => $prg_mode_id,
            'campus' => $campus,
            'sem_id' => $sem_id,
            'date_done' => $date_done
        ]);

        if ($cinsert_semester) {
            $data = array("status" => "200", "message" => "Register On Semester Well Done!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            $data = array("status" => "500", "message" => "Failed To Register On Semester Try Again!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;
        }
    }
}

function download_data(){
    $camp_id=$_POST['camp_id'];
    $prg_type_id=$_POST['prg_type_id'];
    $fac_id=$_POST['fac_id'];
    $dept_id=$_POST['dept_id'];
    $splz_id=$_POST['splz_id'];
    $level_id=$_POST['level_id'];
    $prg_mode_id=$_POST['prg_mode_id'];
    $acad_cycle_id=$_POST['acad_cycle_id'];
    
    
    // $campus_id = $_POST['campus_id'];
    // $program_type_id = $_POST['program_type_id'];
    // $program_id = $_POST['program_id'];
    // $level_id = $_POST['level_id'];
    // $amount = $_POST['amount'];
    // $name = $_POST['name'];
    
    // Return the data as a JSON object
    echo json_encode([
        'status' => 200,
        'camp_id' => $camp_id,
        'prg_type_id' => $prg_type_id,
        'fac_id' => $fac_id,
        'dept_id' => $dept_id,
        'splz_id'=>$splz_id,
        'level_id' => $level_id,
        'Mode' => $prg_mode_id,
        'Academic'=>$acad_cycle_id,
    ]);
    exit;
}

function upload_csv() {
    set_time_limit(300); // Set to 5 minutes or more
    $csvData = json_decode($_POST['csv_data'], true);
    $batchSize = 100; // Number of rows to insert in each batch
    $insertCount = 0;
    $rows1 = [];
    $placeholders1 = [];
    $rows2 = [];
    $placeholders2 = [];
    $rows3 = [];
    $placeholders3 = [];

    try {
        $this->connect->beginTransaction();

        // Fetch the latest registration number
        $currentYear = date('Y');
        $templ = "NU" . substr($currentYear, -2);
        $checkcode = $this->connect->prepare("SELECT reg_no FROM tbl_admission WHERE reg_no LIKE ? ORDER BY adm_id DESC LIMIT 1");
        $checkcode->execute(["%$templ%"]);
        $codeData = $checkcode->fetch();

        // Extract the latest suffix and initialize counter
        $prevSuffix = $codeData ? (int)substr($codeData['reg_no'], -4) : 0;

        foreach ($csvData as $row) {
            $data = str_getcsv($row); // Parse CSV row

            // Check if row has at least 12 columns
            if (count($data) < 4) {
                continue; // Skip rows with missing columns
            }

            // Extract data
            $fname = $data[0];
            $lname = $data[1];
            $gender = $data[2];
            $old_reg_no = trim($data[3]);
            $camp_id = $_POST['camp_id'] ?? null;
            $prg_type_id = $_POST['prg_type_id'] ?? null;
            $fac_id = $_POST['fac_id'] ?? null;
            $dept_id = $_POST['dept_id'] ?? null;
            $splz_id = $_POST['splz_id'] ?? null;
            $level_id = $_POST['level_id'] ?? null;
            $prg_mode_id = $_POST['prg_mode_id'] ?? null;
            $acad_cycle_id = $_POST['acad_cycle_id'] ?? null;
            // $camp_id = $data[4];
            // $prg_type_id = $data[5];
            // $fac_id = $data[6];
            // $dept_id = $data[7];
            // $splz_id = $data[8];
            // $level_id = $data[9];
            // $prg_mode_id = $data[10];
            // $acad_cycle_id = $data[11];
            
            if (!empty($old_reg_no)) {
                // Use the existing registration number
                $reg_no = $old_reg_no;
            } else {
                // Generate a new registration number
                $prevSuffix++;
                $suffix = str_pad($prevSuffix, 4, '0', STR_PAD_LEFT);
                $reg_no = $templ . $fac_id . $suffix;
            }
            
            $username = $reg_no . "@njala.edu.sl";
            $dir = ['cost' => 12];
            $password = password_hash($reg_no, PASSWORD_BCRYPT, $dir);
            $role = '4';

            // Add data to the batch
            $rows1[] = [$reg_no, $acad_cycle_id, $fname, $lname, $gender];
            $placeholders1[] = "(?, ?, ?, ?, ?)";
            $rows2[] = [$reg_no, $acad_cycle_id, $prg_type_id, $splz_id, $level_id, $prg_mode_id, $prg_type_id, $fac_id, $dept_id, $camp_id, $old_reg_no];
            $placeholders2[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $rows3[] = [$reg_no, $lname, $fname, $username, $password, $role];
            $placeholders3[] = "(?, ?, ?, ?, ?, ?)";
            $insertCount++;

            // Insert batch if it reaches the defined size
            if ($insertCount % $batchSize === 0) {
                $stmt1 = $this->connect->prepare("INSERT INTO tbl_admission (reg_no, acad_cycle_id, fname, lname, gender) VALUES " . implode(", ", $placeholders1));
                $stmt1->execute(array_merge(...$rows1));

                $stmt2 = $this->connect->prepare("INSERT INTO tbl_register_program_ug (reg_no, acad_cycle_id, prg_id, splz_id, level_id, prg_mode_id, prg_type, fac_id, dept_id, camp_id, old_reg_no) VALUES " . implode(", ", $placeholders2));
                $stmt2->execute(array_merge(...$rows2));

                $stmt3 = $this->connect->prepare("INSERT INTO tbl_users (Identification, family_name, first_name, email, password, role_id) VALUES " . implode(", ", $placeholders3));
                $stmt3->execute(array_merge(...$rows3));

                // Reset for the next batch
                $rows1 = [];
                $placeholders1 = [];
                $rows2 = [];
                $placeholders2 = [];
                $rows3 = [];
                $placeholders3 = [];
            }
        }

        // Insert any remaining rows
        if (!empty($rows1)) {
            $stmt1 = $this->connect->prepare("INSERT INTO tbl_admission (reg_no, acad_cycle_id, fname, lname, gender) VALUES " . implode(", ", $placeholders1));
            $stmt1->execute(array_merge(...$rows1));

            $stmt2 = $this->connect->prepare("INSERT INTO tbl_register_program_ug (reg_no, acad_cycle_id, prg_id, splz_id, level_id, prg_mode_id, prg_type, fac_id, dept_id, camp_id, old_reg_no) VALUES " . implode(", ", $placeholders2));
            $stmt2->execute(array_merge(...$rows2));

            $stmt3 = $this->connect->prepare("INSERT INTO tbl_users (Identification, family_name, first_name, email, password, role_id) VALUES " . implode(", ", $placeholders3));
            $stmt3->execute(array_merge(...$rows3));
        }

        $this->connect->commit();
        echo json_encode(['status' => 200, 'message' => 'Data inserted successfully.']);
    } catch (Exception $e) {
    error_log("SQL Error: " . $e->getMessage()); // Log error for debugging
    $this->connect->rollBack();
    echo json_encode(['status' => 500, 'message' => 'Error inserting data: ' . $e->getMessage()]);
}

    exit;
}



// Function to split names
function split_name($fullName) {
    // Split the name by the comma
    $nameParts = explode(',', $fullName);

    // Check if we have both parts (last name and other names)
    if (count($nameParts) === 2) {
        $lastName = trim($nameParts[0]); // Last name (before the comma)
        $otherNames = trim($nameParts[1]); // First, middle, suffix (after the comma)
    } else {
        // If no comma is found, assume the full name is given
        $lastName = $fullName;
        $otherNames = '';
    }

    return [
        'last_name' => $lastName,
        'other_names' => $otherNames,
    ];
}








    }	
	$student=new Student();
    $action = $_POST['action'];
	switch($action){
	    case 'search':
	        $student->search();
	        break;
	    case 'search_applicant':
	        $student->search_app();
	        break;     
	    case 'load_info':
	        $student->load_student_info();
	        break;
	    case 'load_academic_info':
	        $student->load_academic_info();
	        break;
	    case 'load_pass_info':
	        $student->load_pass_info();
	        break;
	    case 'load_sponsor_info':
	        $student->load_sponsor_info();
	        break;
	    case 'load_fees':
	        $student->load_invoices();
	        break;
	    case 'update_personal':
	        $student->update_profile_personal();
	        break;
	    case 'update_contact':
	        $student->update_profile_contact();
	        break;
	    case 'update_address':
	        $student->update_profile_address();
	        break;
	    case 'load_levels':
	        $student->load_levels();
	        break;
	    case 'load_info_payment':
	        $student->load_student_info_payment();
	        break;
	    case 'load_application_info_payment':
	        $student->load_application_info();
	        break;
	   case 'save_semester':
	        $student->save_on_semester();
	        break;
	   case 'download_form_data':
		    $student->download_data();
		    break;
	   case 'upload_csv_data':
		    $student->upload_csv();
		    break;	    
	        
	}

?>