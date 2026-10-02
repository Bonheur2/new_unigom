  <form id="save_promo" action="save_promo" method="POST">
 <div class="modal fade"  role="dialog" id="exampleModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"> <?php   echo $data['fname']." ".$data['lname'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                               <div class="form-group col-12 col-sm-12 col-lg-12"   id="dept">
                                                    <label>Year</label>
                                                    <select class="form-control select2" name="year_id" id="year_id" style="width:100%" required readonly>
                                                        
                                                        <?php  
                                                           $sql=$conn->prepare("SELECT tbl_level.*,
                                                    tbl_program_type.prg_type_full_name
                                                    FROM tbl_level 
                                                    INNER JOIN tbl_program_type ON tbl_level.prg_type=tbl_program_type.prg_type_id 
                                                    WHERE tbl_program_type.status=1 AND prg_type='".$data2['prg_type']."' and  level_id >'".$data2['level_id']."' ORDER BY tbl_level.level_id ASC limit 1");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($lvs=$sql->fetch()){
                                                        ?>
                                                        <option value="<?php echo $lvs['level_id']; ?>"><?php echo $lvs['level_full_name']." | ".$lvs['prg_type_full_name']; ?> </option>
                                         
                                                        <?php } ?>
                                                    </select>
                                                </div>
                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                <input type="hidden" name="fac_id" value="<?php echo $data2['fac_id'] ?>">
                                 <input type="hidden" name="reg_no" value="<?php echo $data2['reg_no'] ?>">
                                 <input type="hidden" name="intake_id2" value="<?php echo $data2['intake_id'] ?>">
                                    <label>Specialization</label>
                                    <select class="form-control select2" name="spec_id" id="spec_id" style="width:100%" required placeholder="Choose one">
                                                      
                                    <?php
                                            $sql_prg=$conn->prepare("SELECT 
                                            tbl_specialization.* 
                                            FROM  tbl_specialization  where state=1 and  tbl_specialization.fac_id='".$data2['fac_id']."' and splz_id='".$data2['splz_id']."'
                                            ORDER BY  tbl_specialization.splz_id ASC");
                                            $sql_prg->execute();
                                            $i=1;
                                            while($progs_faculty=$sql_prg->fetch()){
                                                                ?>
                                            <option value="<?php echo $progs_faculty['splz_id']; ?>"><?php echo $progs_faculty['splz_full_name']." | ".$progs_faculty['splz_short_name']; ?> </option>
                                         
                                         <?php } ?>
                                        </select>
                                     <span id="typecont"></span>
                                    </div>
                                                
                                             
                                                <div class="form-group col-12 col-sm-12 col-lg-12"   id="dept">
                                                    <label>Intake</label>
                                                    <select class="form-control select2" name="intake_id" id="intake_id" style="width:100%" required>
                                                            <?php  
                                                           $sql=$conn->prepare("SELECT tbl_intake.*,
                                                    tbl_acad_cycle.acad_year
                                                    FROM tbl_intake 
                                                    INNER JOIN tbl_acad_cycle ON tbl_intake.acad_cycle_id=tbl_acad_cycle.acad_cycle_id 
                                                    WHERE tbl_intake.status=1 and tbl_intake.intake_id>'".$data2['intake_id']."' AND tbl_intake.prg_type='".$data2['prg_type']."' ORDER BY tbl_intake.intake_id ASC");
                                                    $sql->execute();
                                                    $i=1;
                                                    while($lvs=$sql->fetch()){
                                                        ?>
                                                        <option value="<?php echo $lvs['intake_id']; ?>"><?php echo $lvs['intake_month']." | ".$lvs['acad_year']; ?> </option>
                                         
                                                        <?php } ?>
                                                    </select>
                                                        
                                                    </select>
                                                </div>
                                                <div class="form-group col-12 col-sm-12 col-lg-12"   id="dept">
                                                <label>Mode </label>
                                                <select class="form-control select2" name="mode_id" id="mode_id" style="width:100%" required>

                                                <?php  
                                                $sqlMode=$conn->prepare("SELECT * FROM tbl_program_mode  where status=1 order by prg_mode_id asc");
                                                    $sqlMode->execute();
                                                    
                                                    while($lvsMode=$sqlMode->fetch()){ 
                                                        ?>
                                                        <option value="<?php echo $lvsMode['prg_mode_id']; ?>"><?php echo $lvsMode['prg_mode_full_name']; ?> </option>
                                         
                                                        <?php } ?>
                                                    </select>
                                                </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeM">Close</button>
                            <button type="submit" class="btn btn-primary" name="promote-one" id="comfbutton">comfirm</button>
                        </div>
                    </div>
                </div>
            </div>  
            </form>