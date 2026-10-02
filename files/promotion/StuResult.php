<?php 
 if(!empty($_REQUEST['regno'])){
   $regNo=$_REQUEST['regno'];  
 $stmt = $conn->prepare("SELECT tbl_admission.*,tbl_nationality.nationality as nat,
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
                                                                 WHERE tbl_admission.reg_no='".$regNo."' ");
   $stmt->execute();
   $data=$stmt->fetch();
   
   
          $sql=$conn->prepare("SELECT
                                    tbl_register_program_ug.*,
                                    tbl_program_type.prg_type_full_name,
                                    tbl_faculty.fac_full_name,
                                    tbl_department.dept_full_name,
                                    tbl_specialization.splz_full_name,
                                    tbl_level.level_full_name,
                                    tbl_program_mode.prg_mode_full_name
                                        FROM tbl_register_program_ug
                                    INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                    INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                    INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                    INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
                                    WHERE tbl_register_program_ug.reg_no = '".$regNo."' ORDER BY tbl_register_program_ug.reg_prg_id DESC");
            $sql->execute();
            $data2=$sql->fetch();
            
                

?>
  <div class="row">
                            <div class="col-lg-12">
                                
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Personal info:</strong><br>
                                            RegNo: <b><?php echo $_POST['stu'] ?></b><br>
                                            Names: <?php echo $data['lname']." ".$data['fname'] ?><br>
                                             Gender: <?php echo $data['gender'] ?><br>
                                            Phone: <?php echo $data['phone'] ?><br>
                                           Email: <?php echo $data['email'] ?>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Address Info:</strong><br>
                                            Country: <?php echo $data['cname'] ?><br>
                                             Province: <?php echo $data['pname'] ?><br>
                                            District: <?php echo $data['dname'] ?><br>
                                            Sector: <?php echo $data['sname'] ?>
                                        </address>
                                    </div>
                                </div>
                             
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                         
                            <div class="col-md-12">
                                    
                                <div class="section-title">Academic information.</div>
                                <p class="section-lead"><?php echo $data2['prg_type_full_name']  ?></p>
                                <br>
                                <div class="card">
                                 <div class="card-body collapse show" id="mycard-collapse4">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <tr>
                                            <th>#</th>
                                            <th>Faculty</th>
                                            <th >Department</th>
                                            <th >Specialzization</th>
                                            <th >Level</th>
                                            <th>Intake</th>
                                            <th class="text-center"></th>
                                            <td></td>
                                        </tr>
                                        
                                        <?php   
                             $sql2=$conn->prepare("SELECT
                                    tbl_register_program_ug.*,
                                    tbl_program_type.prg_type_full_name,
                                    tbl_faculty.fac_full_name,
                                    tbl_department.dept_full_name,
                                    tbl_specialization.splz_full_name,
                                    tbl_level.level_full_name,
                                    tbl_program_mode.prg_mode_full_name
                                        FROM tbl_register_program_ug
                                    INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                    INNER JOIN tbl_faculty ON tbl_register_program_ug.fac_id = tbl_faculty.fac_id
                                    INNER JOIN tbl_department ON tbl_register_program_ug.dept_id = tbl_department.dept_id
                                    INNER JOIN tbl_specialization ON tbl_register_program_ug.splz_id = tbl_specialization.splz_id
                                    INNER JOIN tbl_level ON tbl_register_program_ug.level_id = tbl_level.level_id
                                    INNER JOIN tbl_program_mode ON tbl_register_program_ug.prg_mode_id = tbl_program_mode.prg_mode_id
                                    WHERE tbl_register_program_ug.reg_no = '".$regNo."' ORDER BY tbl_register_program_ug.reg_prg_id asc");
            $sql2->execute();
            $dataIn=0;
            
            while($data22=$sql2->fetch()){
                $dataIn++;
                
                $sqlIN=$conn->prepare("SELECT tbl_intake.*,
                                                    tbl_acad_cycle.acad_year
                                                    FROM tbl_intake 
                                                    INNER JOIN tbl_acad_cycle ON tbl_intake.acad_cycle_id=tbl_acad_cycle.acad_cycle_id 
                                                    WHERE tbl_intake.intake_id='".$data22['intake_id']."' ORDER BY tbl_intake.intake_id ASC");
                                                    $sqlIN->execute();
                                                    $i=1;
                                                    $lvsIN=$sqlIN->fetch();
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $dataIn ?></td>
                                            <td><?php echo $data22['fac_full_name'] ?></td>
                                            <td class="text-center"><?php echo $data22['dept_full_name'] ?></td>
                                            <td class="text-center"><?php echo $data22['splz_full_name'] ?></td>
                                            <td class="text-left"><?php echo $data22['level_full_name'] ?></td>
                                            <td> <?php echo $lvsIN['intake_month']." | ".$lvsIN['acad_year']; ?></td>
                                            <td class="text-right" align="right">
                                                 <div class="buttons row">
                                                            <!--<button type="button" data-id="<?php echo $data22['reg_prg_id']; ?>" class="btn btn-icon btn-primary btn-sm edit"><span id="spinner4_<?php echo $data22['reg_prg_id']; ?>"></span>&nbsp;<i class="far fa-edit"></i>&nbsp;edit</button>-->
                                                            <label class="custom-switch btn btn-light btn-sm">
                                                                <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input del" data-id="<?php echo $data22['reg_prg_id']; ?>" <?php echo $data22['reg_active']==1?'checked':''; ?>>
                                                                <span class="custom-switch-indicator"></span><span id="spinner3_<?php echo $data22['reg_prg_id']; ?>"></span>&nbsp;
                                                            </label>
                                                        </div>
                                            </td>
                                            <td>
                                                
                                            </td>
                                            
                                        </tr>
                                      <?php } ?>
                                    </table>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php  if($sql2->rowCount()>0){ ?>
                    <div class="text-md-right">
                        <!--<div class="float-lg-left mb-lg-0 mb-3">-->
                        <!--    <button class="btn btn-primary btn-icon icon-left"><i class="fas fa-credit-card"></i> Process Payment</button>-->
                        <!--    <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</button>-->
                        <!--</div>-->
                        <button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#exampleModal"><i class="far fa-arrow-alt-circle-up"></i> Promote</button>
                    </div>
                    
                    
                    
                    <?php
                   
                    } 
                    
                    }?>

