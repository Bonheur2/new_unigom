<?php
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=Sponsorship.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

include "../../meet/con.php";
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
    }
    
    th, td{
        width: max-content;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>
    <table class="table table-striped" border="1">
        <thead>
            <?php
                if($_REQUEST['spn'] != "all"){
                    $query ="SELECT * FROM tbl_sponsor WHERE spon_id = :sponsor";
                    $getSponsor = $conn->prepare($query);
                    $getSponsor->bindParam(':sponsor', $_REQUEST['spn'], PDO ::PARAM_INT);
                    $getSponsor->execute();
                    $sponsor_data = $getSponsor->fetch();
                    $text = $sponsor_data['spon_full_name']." Beneficiaries";
                } else{
                    $text = "SPONSORED Students";
                }
            ?>
            <tr>
                <th colspan="22" class="top"><?=$text; ?></th>
            </tr>
            
            <tr>
                <th rowspan="2">S/N</th>
                <th rowspan="2">LEVEL</th>
                <th rowspan="2">SPECIALIZATION</th>
                <th rowspan="2">FIRSTNAME</th>
                <th rowspan="2">MIDDLE NAME</th>
                <th rowspan="2">LASTNAME</th>
                <th rowspan="2">REG. NUMBER</th>
                <th rowspan="2">ID/PASSPORT</th>
                <th rowspan="2">TEL</th>
                <th colspan="3" class="cb">DATE OF BIRTH AND NATIONALITY</th>
                <th colspan="6">PHYSICAL LOCATION</th>
                <th colspan="2">PARENTS(FATHER)</th>
                <th colspan="2">PARENTS(MOTHER)</th>
            </tr>
            <tr>
                <th>DATE OF BIRTH</th>
                <th>COUNTRY OF BIRTH</th>
                <th>NATIONALITY</th>
                <th>COUNTRY</th>
                <th>PROVINCE</th>
                <th>DISTRICT</th>
                <th>SECTOR</th>
                <th>CELL</th>
                <th>VILLAGE</th>
                <th>FATHER NAMES</th>
                <th>TEL (IF ALIVE)</th>
                <th>MOTHER'S NAMES</th>
                <th>TEL (IF ALIVE)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($_REQUEST['spn'] != "all"){
                
                $counter =0;
                $querry ="SELECT * FROM tbl_register_program_ug WHERE reg_active = :status AND spon_id = :sponsor";
                $status =1;
                $sponsoredSrudents =$conn->prepare($querry);
                $sponsoredSrudents->bindParam(':status', $status, PDO::PARAM_INT);
                $sponsoredSrudents->bindParam(':sponsor', $_REQUEST['spn'], PDO::PARAM_INT);
                if($sponsoredSrudents->execute()){
                    $data=$sponsoredSrudents->fetchAll();
                    foreach($data as $student){
                        // student info 
                        $studentsNames =$conn->prepare("SELECT tbl_admission.*,
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
                                                     WHERE tbl_admission.reg_no= :registration");
                        $studentsNames->bindParam(':registration', $student['reg_no'], PDO::PARAM_STR);
                        if($studentsNames->execute()){
                            $resultSet =$studentsNames->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($resultSet as $entity){
                                $counter += 1;
                                
                                // get prg type
                                $progType =$conn->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id = :type AND status = :status");
                                $progType->bindParam(':type', $student['prg_type'], PDO::PARAM_INT);
                                $progType->bindValue(':status', 1, PDO::PARAM_INT);
                                $progType->execute();
                                $theType =$progType->fetch();
                                
                                
                                // special
                                $spec =$conn->prepare("SELECT * FROM tbl_specialization WHERE splz_id = :splz AND status = :status");
                                $spec->bindValue(':status', 1, PDO::PARAM_INT);
                                $spec->bindParam(':splz', $student['splz_id'], PDO::PARAM_INT);
                                $spec->execute();
                                $theSpec =$spec->fetch();
                                 
                                // intake
                                $intake =$conn->prepare("SELECT * FROM tbl_intake WHERE intake_id = :intake AND status = :status");
                                $intake->bindvalue(':status', 1, PDO::PARAM_INT);
                                $intake->bindParam(':intake', $student['intake_id'], PDO::PARAM_INT);
                                $intake->execute();
                                $theIntake =$intake->fetch();
                                
                                // level
                                $level =$conn->prepare("SELECT * FROM tbl_level WHERE level_id = :level AND status = :status");
                                $level->bindValue(':status', 1, PDO::PARAM_INT);
                                $level->bindParam(':level', $student['level_id'], PDO::PARAM_INT);
                                $level->execute();
                                $theLevel =$level->fetch();
                            ?>
                                <tr>
                                    <td><?php echo $counter; ?></td>
                                    <td><?php echo $theLevel['level_full_name']; ?></td>
                                    <td><?php echo $theSpec['splz_full_name']; ?></td>
                                    <td><?php echo $entity['fname']; ?></td>
                                    <td></td>
                                    <td><?php echo $entity['lname']; ?></td>
                                    <td><?php echo $student['reg_no']; ?></td>
                                    <td><?php echo $entity['ID'] != "NULL"?$entity['ID'] != ""?"'".$entity['ID']:'':''; ?> </td>
                                    <td><?php echo $entity['phone']; ?></td>
                                    
                                    <td><?php echo $entity['dob']; ?></td>
                                    <td><?php echo $entity['cname']; ?></td>
                                    <td><?php echo $entity['nat']; ?></td>
                                    
                                    <td><?php echo $entity['cname']; ?> </td>
                                    <td><?php echo $entity['pname']; ?></td>
                                    <td><?php echo $entity['dname']; ?></td>
                                    <td><?php echo $entity['sname']; ?></td>
                                    <td><?php echo $entity['cellname']; ?></td>
                                    <td><?php echo $entity['vname']; ?></td>
                                    
                                    <td><?php echo $entity['father_names']; ?></td>
                                    <td><?php echo $entity['parent_phone'] != "NULL"?$entity['parent_phone'] != ""?"'".$entity['parent_phone']:'':''; ?> </td>
                                    <td><?php echo $entity['mother_names']; ?></td>
                                    <td><?php echo $entity['ref_phone'] != "NULL"?$entity['ref_phone'] != ""?"'".$entity['ref_phone']:'':''; ?></td>
                                </tr>
                                <?
                            }
                        }
                        
                    }
                }
                // for all
            }else{
                
                $counter =0;
                $querry ="SELECT * FROM tbl_register_program_ug WHERE reg_active = :status AND spon_id != :execluded";
                $status =1;
                $sponsoredSrudents =$conn->prepare($querry);
                $sponsoredSrudents->bindParam(':status', $status, PDO::PARAM_INT);
                $sponsoredSrudents->bindValue(':execluded', 13, PDO::PARAM_INT);
                if($sponsoredSrudents->execute()){
                    $data=$sponsoredSrudents->fetchAll();
                    foreach($data as $student){
                        
                        // student info 
                        $studentsNames =$conn->prepare("SELECT tbl_admission.*,
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
                                                     WHERE tbl_admission.reg_no= :registration");
                        $studentsNames->bindParam(':registration', $student['reg_no'], PDO::PARAM_STR);
                        if($studentsNames->execute()){
                            $resultSet =$studentsNames->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($resultSet as $entity){
                                $counter += 1;
                                
                                // get prg type
                                $progType =$conn->prepare("SELECT * FROM tbl_program_type WHERE prg_type_id = :type AND status = :status");
                                $progType->bindParam(':type', $student['prg_type'], PDO::PARAM_INT);
                                $progType->bindValue(':status', 1, PDO::PARAM_INT);
                                $progType->execute();
                                $theType =$progType->fetch();
                                
                                
                                // special
                                $spec =$conn->prepare("SELECT * FROM tbl_specialization WHERE splz_id = :splz AND status = :status");
                                $spec->bindValue(':status', 1, PDO::PARAM_INT);
                                $spec->bindParam(':splz', $student['splz_id'], PDO::PARAM_INT);
                                $spec->execute();
                                $theSpec =$spec->fetch();
                                
                                // intake
                                $intake =$conn->prepare("SELECT * FROM tbl_intake WHERE intake_id = :intake AND status = :status");
                                $intake->bindvalue(':status', 1, PDO::PARAM_INT);
                                $intake->bindParam(':intake', $student['intake_id'], PDO::PARAM_INT);
                                $intake->execute();
                                $theIntake =$intake->fetch();
                                
                                // level
                                $level =$conn->prepare("SELECT * FROM tbl_level WHERE level_id = :level AND status = :status");
                                $level->bindValue(':status', 1, PDO::PARAM_INT);
                                $level->bindParam(':level', $student['level_id'], PDO::PARAM_INT);
                                $level->execute();
                                $theLevel =$level->fetch();
                                ?>
                                <tr>
                                    <td><?php echo $counter; ?></td>
                                    <td><?php echo $theLevel['level_full_name']; ?></td>
                                    <td><?php echo $theSpec['splz_full_name']; ?></td>
                                    <td><?php echo $entity['fname']; ?></td>
                                    <td></td>
                                    <td><?php echo $entity['lname']; ?></td>
                                    <td><?php echo $student['reg_no']; ?></td>
                                    <td><?php echo $entity['ID'] != "NULL"?$entity['ID'] != ""?"'".$entity['ID']:'':''; ?> </td>
                                    <td><?php echo $entity['phone']; ?></td>
                                    
                                    <td><?php echo $entity['dob']; ?></td>
                                    <td><?php echo $entity['cname']; ?></td>
                                    <td><?php echo $entity['nat']; ?></td>
                                    
                                    <td><?php echo $entity['cname']; ?> </td>
                                    <td><?php echo $entity['pname']; ?></td>
                                    <td><?php echo $entity['dname']; ?></td>
                                    <td><?php echo $entity['sname']; ?></td>
                                    <td><?php echo $entity['cellname']; ?></td>
                                    <td><?php echo $entity['vname']; ?></td>
                                    
                                    <td><?php echo $entity['father_names']; ?></td>
                                    <td><?php echo $entity['parent_phone']; ?> </td>
                                    <td><?php echo $entity['mother_names']; ?></td>
                                    <td><?php echo $entity['ref_phone']; ?></td>
                                </tr>
                                <?
                            }
                        }
                        
                    }
                }
            }
            ?>
        </table>
    </tbody>
</div>