<?php
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=applications.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

include "../../../meet/con.php";
$connection ="";

if($conn){
    $connection = "connected";
}else{
   $connection = "no connection";
}
?>
<style>
    .top{
        color: white; 
        background-color: #1383EB; 
        height: 40px;
        padding: 40px;
        font-size: 20px;
        text-align: center;
    }
    
    th, td{
        width: max-content;
        text-align: left;
    }
</style>

<table class="table table-hover table-sm" border="1">
    <thead>
        <tr>
            <?php
            $faculity=$conn->prepare("SELECT fac_full_name FROM tbl_faculty WHERE fac_id ='".$_REQUEST['fac']."'");
            $faculity->execute();
            $dataFac=$faculity->fetch();
             ?>
            <th colspan="17" class="top">APPLICANTS IN SCHOOL OF [ <?php echo strtoupper($dataFac['fac_full_name']); ?> ]</th>
        </tr>
        <tr>
            <th rowspan="2">S/N</th>
            <th rowspan="2">TRACKING NUMBER</th>
            <th rowspan="2">FIRSTNAME</th>
            <th rowspan="2">LASTNAME</th>
            <th rowspan="2">ID/PASSPORT</th>
            <th rowspan="2">TEL</th>
            <th rowspan="2">EMAIL</th>
            <th rowspan="2">NATIONALITY</th>
            <th colspan="4">CURRENT ADDRESS</th>
            <th colspan="4">PARENTS</th>
            <th rowspan="2">DATE OF APPLICATION</th>
        </tr>
        <tr>
            <th>COUNTRY</th>
            <th>PROVINCE</th>
            <th>DISTRICT</th>
            <th>Street</th>
            <th>FATHER NAMES</th>
            <th>TEL</th>
            <th>MOTHER'S NAMES</th>
            <th>TEL</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql=$conn->prepare("SELECT
                                    distinct(tbl_admittedPRG.Stu_code) as code,
                                    tbl_applicants.*,
                                    tbl_nationality.nationality as nat,
                                    tbl_country.cntr_name as cname,
                                    provinces.provincename as pname,
                                    districts.namedistrict as dname,
                                    sectors.namesector as sname,
                                    cells.namecell as cellname,
                                    villages.VillageName as vname
                                FROM
                                    tbl_applicants
                                INNER JOIN tbl_admittedPRG ON tbl_applicants.code = tbl_admittedPRG.Stu_code
                                LEFT JOIN tbl_nationality ON
                                    tbl_applicants.nationality=tbl_nationality.nat_id
                                LEFT JOIN tbl_country ON
                                    tbl_applicants.country=tbl_country.cntr_id
                                LEFT JOIN provinces ON
                                    tbl_applicants.province_id=provinces.provincecode
                                LEFT JOIN districts ON
                                    tbl_applicants.district_id=districts.districtcode
                                LEFT JOIN sectors ON
                                    tbl_applicants.sector=sectors.sectorcode
                                LEFT JOIN cells ON
                                    tbl_applicants.cell_id=cells.codecell
                                LEFT JOIN villages ON
                                    tbl_applicants.village_id=villages.CodeVillage
                                INNER JOIN  tbl_specialization ON tbl_admittedPRG.dept_id=tbl_specialization.splz_id 
                                WHERE
                                    (tbl_admittedPRG.sts = 2 OR tbl_admittedPRG.sts = 11 ) AND tbl_applicants.submitted!=0 AND tbl_specialization.fac_id='".$_REQUEST['fac']."' ");
            $sql->execute();
            $i=1;
            while($apps=$sql->fetch()){
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $apps['code']; ?></td>
                <td><?php echo $apps['fname']; ?></td>
                <td><?php echo $apps['lname']; ?></td>
                <td><?php echo $apps['ID'] != "NULL"?$apps['ID'] != ""?"'".$apps['ID']:'':''; ?> </td>
                <td><?php echo $apps['phone'] != "NULL"?$apps['phone'] != ""?"'".str_replace('+25', "", $apps['phone']):'':''; ?></td>
                <td><?php echo $apps['email'] ?></td>
                
                <td><?php echo $apps['nat']; ?></td>
                <td><?php echo $apps['cname']; ?> </td>
                <td><?php echo $apps['pname']; ?></td>
                <td><?php echo $apps['dname']; ?></td>
                <td><?php echo $apps['street']; ?></td>
                
                <td><?php echo $apps['father_names']; ?></td>
                <td><?php echo $apps['parent_phone'] != "NULL"?$apps['parent_phone'] != ""?"'".str_replace('+232', "", $apps['parent_phone']):'':'';; ?> </td>
                <td><?php echo $apps['mother_names']; ?></td>
                <td><?php echo $apps['ref_phone'] != "NULL"?$apps['ref_phone'] != ""?"'".str_replace('+232', "", $apps['ref_phone']):'':'';; ?></td>
                <td><?php echo $apps['createdAt']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>