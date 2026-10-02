<?php
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=applications_" . date('Y-m-d_His') . ".xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

include "../../meet/con.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = "";

if($conn){
    $connection = "connected";
}else{
   die("Database connection failed");
}

$selectedYear = isset($_GET['acad_year']) ? trim($_GET['acad_year']) : '';
$isFilteredByYear = $selectedYear !== '';

if ($isFilteredByYear) {
    $safeFileYear = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $selectedYear);
    header("Content-Disposition: attachment; filename=applications_" . $safeFileYear . "_" . date('Y-m-d_His') . ".xls");
}
?>
<style>
.top {
    color: white;
    background-color: #052df5;
    height: 40px;
    padding: 10px;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
}

th {
    background-color: #052df5;
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 8px;
}

td {
    padding: 5px;
    text-align: left;
    border: 1px solid #ddd;
}

.header-row {
    background-color: #052df5;
    color: white;
    font-weight: bold;
}
</style>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th colspan="16" class="top">NJALA UNIVERSITY - APPLICANTS LIST</th>
        </tr>
        <tr class="header-row">
            <th>S/N</th>
            <th>TRACKING NUMBER</th>
            <th>APPLICANT NAMES</th>
            <th>ACADEMIC YEAR</th>
            <th>PHONE NUMBER</th>
            <th>EMAIL</th>
            <th>SEX</th>
            <th>CAMPUS</th>
            <th>PROGRAM</th>
            <th>SCHOOL</th>
            <th>DEPARTMENT</th>
            <th>SPECIALIZATION</th>
            <th>LOCATION</th>
            <th>WAEC RESULT 1</th>
            <th>PIN 1</th>
            <th>WAEC RESULT 2</th>
            <th>PIN 2</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $query = "SELECT
                        tbl_applicants.code,
                        tbl_applicants.fname,
                        tbl_applicants.mname,
                        tbl_applicants.lname,
                        tbl_acad_cycle.acad_year,
                        tbl_applicants.phone,
                        tbl_applicants.email,
                        tbl_applicants.gender,
                        tbl_country.cntr_name as cname,
                        provinces.provincename as pname,
                        districts.namedistrict as dname,
                        sectors.namesector as sname,
                        MAX(tbl_campus.camp_full_name) as camp_full_name,
                        MAX(tbl_program_type.prg_type_full_name) as prg_type_full_name,
                        MAX(tbl_faculty.fac_full_name) as fac_full_name,
                        MAX(tbl_department.dept_full_name) as dept_full_name,
                        MAX(tbl_specialization.splz_full_name) as splz_full_name,
                        GROUP_CONCAT(DISTINCT CONCAT(tbl_department.dept_full_name, ' - ', tbl_specialization.splz_full_name) SEPARATOR ' | ') as all_programs,
                        COUNT(DISTINCT tbl_admittedPRG.Aprg_id) as app_count
                    FROM tbl_applicants
                    INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                    LEFT JOIN tbl_country ON tbl_applicants.country = tbl_country.cntr_id
                    LEFT JOIN provinces ON tbl_applicants.province_id = provinces.provincecode
                    LEFT JOIN districts ON tbl_applicants.district_id = districts.districtcode
                    LEFT JOIN sectors ON tbl_applicants.sector = sectors.sectorcode
                    INNER JOIN tbl_campus ON tbl_admittedPRG.cump_id = tbl_campus.camp_id
                    INNER JOIN tbl_program_type ON tbl_admittedPRG.prg_type = tbl_program_type.prg_type_id
                    INNER JOIN tbl_faculty ON tbl_admittedPRG.fac_id = tbl_faculty.fac_id
                    INNER JOIN tbl_department ON tbl_admittedPRG.dept_id = tbl_department.dept_id
                    INNER JOIN tbl_specialization ON tbl_admittedPRG.splz = tbl_specialization.splz_id
                        INNER JOIN tbl_acad_cycle ON tbl_admittedPRG.acad_id = tbl_acad_cycle.acad_cycle_id
                    WHERE tbl_applicants.submitted != 10
                        ";

                    if ($isFilteredByYear) {
                    $query .= " AND tbl_acad_cycle.acad_year = :acad_year ";
                    }

                    $query .= "GROUP BY tbl_applicants.code, tbl_acad_cycle.acad_year
                        ORDER BY tbl_acad_cycle.acad_year DESC, tbl_applicants.code";
                    
            $sql = $conn->prepare($query);

                    if ($isFilteredByYear) {
                    $sql->bindValue(':acad_year', $selectedYear);
                    }

            $sql->execute();
            $i = 1;
            
            while($apps = $sql->fetch()){
                // Get WAEC results
                $waec_query = $conn->prepare("SELECT waec_result1, pin1, waec_resilt2, pin2 FROM tbl_applicant_education WHERE stu = ? LIMIT 1");
                $waec_query->execute([$apps['code']]);
                $waec_data = $waec_query->fetch();
                
                // Format phone number
                $phone = $apps['phone'];
                if(!empty($phone) && $phone != "NULL") {
                    $phone = "'" . str_replace('+232', '', $phone);
                } else {
                    $phone = '';
                }
                
                // Build location string
                $location = '';
                if(!empty($apps['pname'])) $location .= $apps['pname'];
                if(!empty($apps['dname'])) $location .= (!empty($location) ? ', ' : '') . $apps['dname'];
                if(!empty($apps['sname'])) $location .= (!empty($location) ? ', ' : '') . $apps['sname'];
                if(empty($location)) $location = $apps['cname'];
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($apps['code']); ?></td>
            <td><?php echo htmlspecialchars($apps['fname'] . ' ' . ($apps['mname'] ?? '') . ' ' . $apps['lname']); ?>
            </td>
            <td><?php echo htmlspecialchars($apps['acad_year']); ?></td>
            <td><?php echo $phone; ?></td>
            <td><?php echo htmlspecialchars($apps['email']); ?></td>
            <td><?php echo $apps['gender'] == 'M' ? 'Male' : 'Female'; ?></td>
            <td><?php echo htmlspecialchars($apps['camp_full_name']); ?></td>
            <td><?php echo htmlspecialchars($apps['prg_type_full_name']); ?></td>
            <td><?php echo htmlspecialchars($apps['fac_full_name']); ?></td>
            <td><?php echo htmlspecialchars($apps['dept_full_name']); ?></td>
            <td><?php echo $apps['app_count'] > 1 ? htmlspecialchars($apps['all_programs']) : htmlspecialchars($apps['splz_full_name']); ?>
            </td>
            <td><?php echo htmlspecialchars($location); ?></td>
            <td><?php echo $waec_data ? htmlspecialchars($waec_data['waec_result1']) : 'N/A'; ?></td>
            <td><?php echo $waec_data ? htmlspecialchars($waec_data['pin1']) : 'N/A'; ?></td>
            <td><?php echo $waec_data ? htmlspecialchars($waec_data['waec_resilt2']) : 'N/A'; ?></td>
            <td><?php echo $waec_data ? htmlspecialchars($waec_data['pin2']) : 'N/A'; ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="17" style="text-align: center; font-weight: bold; background-color: #f0f0f0; padding: 10px;">
                Scope: <?php echo $isFilteredByYear ? 'Academic Year - ' . htmlspecialchars($selectedYear) : 'All Academic Years'; ?> |
                Total Applicants: <?php echo ($i - 1); ?> |
                Generated on: <?php echo date('Y-m-d H:i:s'); ?>
            </td>
        </tr>
    </tbody>
</table>