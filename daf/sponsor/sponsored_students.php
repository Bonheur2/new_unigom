<?php
require_once "../../meet/bind.php";

?>
<div class="form-group">
    <table class="table table-striped v_center" id="table-1" border="1">
        <center>
            <thead>
                <th>Serial</th>
                <th>Identification</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Program type</th>
                <th>Specialization</th>
                <th>Intake</th>
                <th>level</th>
            </thead>
        </center>
        <tbody>
            <?php
            if($_REQUEST['sponsor'] != "all"){
                
                $counter =0;
                $querry ="SELECT * FROM tbl_register_program_ug WHERE reg_active = :status AND spon_id = :sponsor";
                $status =1;
                $sponsoredSrudents =$conn->prepare($querry);
                $sponsoredSrudents->bindParam(':status', $status, PDO::PARAM_INT);
                $sponsoredSrudents->bindParam(':sponsor', $_REQUEST['sponsor'], PDO::PARAM_INT);
                if($sponsoredSrudents->execute()){
                    $data=$sponsoredSrudents->fetchAll();
                    foreach($data as $student){
                        // student info 
                        $studentsNames =$conn->prepare("select lname, fname from tbl_admission where reg_no = :registration");
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
                                    <td><?php echo $counter;?></td>
                                    <td><?php echo $student['reg_no'];?> </td>
                                    <td><?php echo $entity['lname'];?></td>
                                    <td><?php echo $entity['fname'];?></td>
                                    <td><?php echo $theType['prg_type_full_name'];?></td>
                                    <td><?php echo $theSpec['splz_full_name'];?> </td>
                                    <td><?php echo $theIntake['intake_month'];?></td>
                                    <td><?php echo $theLevel['level_full_name'];?></td>
                                </tr>
                                <?
                            }
                        }
                        
                    }
                }
                // for all
            }else{
                
                $counter =0;
                // echo $_REQUEST['sponsor'];
                $querry ="SELECT * FROM tbl_register_program_ug WHERE reg_active = :status AND spon_id != :execluded";
                $status =1;
                $sponsoredSrudents =$conn->prepare($querry);
                $sponsoredSrudents->bindParam(':status', $status, PDO::PARAM_INT);
                $sponsoredSrudents->bindValue(':execluded', 13, PDO::PARAM_INT);
                if($sponsoredSrudents->execute()){
                    $data=$sponsoredSrudents->fetchAll();
                    foreach($data as $student){
                        
                        // student info 
                        $studentsNames =$conn->prepare("select lname, fname from tbl_admission where reg_no = :registration");
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
                                    <td><?php echo $counter;?></td>
                                    <td><?php echo $student['reg_no'];?> </td>
                                    <td><?php echo $entity['lname'];?></td>
                                    <td><?php echo $entity['fname'];?></td>
                                    <td><?php echo $theType['prg_type_full_name'];?></td>
                                    <td><?php echo $theSpec['splz_full_name'];?> </td>
                                    <td><?php echo $theIntake['intake_month'];?></td>
                                    <td><?php echo $theLevel['level_full_name'];?></td>
                                </tr>
                                <?
                            }
                        }
                        
                    }
                }
            }
            ?>
        </table>
        <a href="/files/Student_reports/download_xls.php?spn=<?php echo $_REQUEST['sponsor']; ?>" class="btn btn-success btn-sm m-20"><i class="fa fa-download"></i>&nbsp;Excel</a>
    </tbody>
</div>
<script>
    $(document).ready( function () {
        $('#table-1').DataTable();
    } );
</script>