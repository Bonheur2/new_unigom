<?php
include ('../../meet/con.php');

$reg_no = $_REQUEST['stu'];
$intake = $_REQUEST['intake'];
$fee = $_REQUEST['fee'];

//Get basic debt data
$sql=$conn->prepare("SELECT a.fname, a.lname,i.intake_month,ac.acad_year, r.reg_no, s.splz_full_name, l.level_full_name
                                FROM tbl_register_program_ug r
                                    INNER JOIN tbl_admission a ON r.reg_no = a.reg_no
                                    INNER JOIN tbl_intake i ON r.intake_id = i.intake_id
                                    INNER JOIN tbl_acad_cycle ac ON i.acad_cycle_id = ac.acad_cycle_id
                                    INNER JOIN tbl_specialization s ON r.splz_id = s.splz_id
                                    INNER JOIN tbl_level l ON r.level_id = l.level_id
                                WHERE
                                    r.reg_no = '".$reg_no."' AND r.intake_id='".$intake."'");
$sql->execute(); 
$data=$sql->fetch();




//get debt info
$debtData=$conn->prepare("SELECT SUM(inv.balance) as debt, f.name as fee_name
                                FROM tbl_invoice inv
                                    INNER JOIN fee_category f ON inv.fee_id = f.id
                                WHERE
                                    inv.reg_no = '".$reg_no."' AND inv.intake_id='".$intake."' AND inv.fee_id='".$fee."'");
$debtData->execute();
$debtBalance=$debtData->fetch();
$debt=$debtBalance['debt'];

$payHistory=$conn->prepare("SELECT SUM(amount) as paid FROM payment WHERE reg_no = '".$reg_no."' AND intake_id='".$intake."' AND fee_id='".$fee."' AND status=1;");
$payHistory->execute();
$historyBalance=$payHistory->fetch();
$paid=$historyBalance['paid'];


//get all all descriptions
if($fee==2){
$sql2=$conn->prepare("SELECT r.room_code,b.block_name,i.balance 
                            FROM tbl_invoice i 
                            INNER JOIN tbl_hostel_room r ON i.room_id=r.room_id
                            INNER JOIN tbl_hostel_block b ON r.block_id=b.block_id
                            WHERE i.reg_no = '".$reg_no."' AND i.intake_id='".$intake."' AND i.fee_id='".$fee."'");
$sql2->execute();
}

else if($fee==3){
$sql2=$conn->prepare("SELECT r.rest_name,rc.class_name,i.month,i.balance 
                            FROM tbl_invoice i 
                            INNER JOIN tbl_restaurant r ON i.restaurant_id=r.rest_id
                            INNER JOIN tbl_rest_class rc ON r.class_id=rc.class_id
                            WHERE i.reg_no = '".$reg_no."' AND i.intake_id='".$intake."' AND i.fee_id='".$fee."'");
$sql2->execute();
}

else if($fee==6){
$sql2=$conn->prepare("SELECT balance,comment FROM tbl_invoice  WHERE reg_no = '".$reg_no."' AND intake_id='".$intake."' AND fee_id='".$fee."'");
$sql2->execute();
}
?>
<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
            <tr>
                <th>Student ID:</th>
                <th><?php echo $reg_no; ?></th>
            </tr>
            <tr>
                <th>Names:</th>
                <th><?php echo $data['fname']." ".$data['lname']; ?></th>
            </tr>
            <tr>
                <th>Specialization:</th>
                <th><?php echo $data['splz_full_name']; ?></th>
            </tr>
            <tr>
                <th>Level:</th>
                <th><?php echo $data['level_full_name']; ?></th>
            </tr>
            <tr>
                <th>Intake Month:</th>
                <th><?php echo $data['intake_month']." | ".$data['acad_year']; ?></th>
            </tr>
            <tr>
                <th>Fee category:</th>
                <th><?php echo $debtBalance['fee_name']; ?></th>
            </tr>
            <tr>
                <th><i>Invoice description:</i></th>
                <th>
                    <?php
                        if($fee==1){
                            echo "<span class='badge badge-success'>Enrolled modules</span>";
                        }
                        if($fee==2){
                            while($desc=$sql2->fetch()){
                                echo "<span class='badge badge-success'>".$desc['room_code']." ( ".$desc['block_name']." ) | ".number_format($desc['balance'],2)."</span>";
                            }
                        }
                        if($fee==3){
                            while($desc=$sql2->fetch()){
                                echo "<span class='badge badge-success'>".$desc['rest_name'].", ".$desc['class_name']." ( ".$desc['month']." ) | ".number_format($desc['balance'],2)."</span>";
                            }
                        }
                        else if($fee==6){
                            while($desc=$sql2->fetch()){
                                echo "<span class='badge badge-success'>".$desc['comment']." | ".number_format($desc['balance'],2)."</span>";
                            }
                        }
                        
                        $balance=$debt-$paid;
                    ?>
                    
                </th>
            </tr>
            <tr>
                <th>Unpaid amount:</th>
                <th><?php echo number_format($balance,2); ?></th>
            </tr>
        </thead>
    </table>    
</div>
