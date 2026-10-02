<?php
/**
 * 
 * Author @macintosh
 */
 ?>
<?php include('../../meet/con.php');?>
    
<?php
    
    // request vars
    $id =$_REQUEST['identity'];
    $module =$_REQUEST['module'];
    
    $date =date('Y-m-d');
    $timestamp = strtotime($date);
    $dayName = date("l", $timestamp);
    
    // find out the program type and program mode of incoming spec
    $day =$conn->prepare("SELECT *from tbl_t_days where day_name = :day");
    $day->bindParam(':day', $dayName);
    $day->execute();
    $theday =$day->fetch();    
    
    $spec =$conn->prepare("SELECT * from tbl_t_schedule where learning_day_id = :learn_day and class_module_id
    IN(SELECT mod_id from tbl_modules where module_id
    IN(SELECT module_id from tbl_module_leader where staff_id = :staff) and mod_id =:mod_id) and class_module_id =:class_module");
    
    $spec->bindParam(':learn_day', $theday['day_id'], PDO::PARAM_INT);
    $spec->bindParam(':staff', $id, PDO::PARAM_STR);
    $spec->bindParam(':mod_id', $module, PDO::PARAM_INT);
    $spec->bindParam(':class_module', $module, PDO::PARAM_INT);
    $spec->execute();
    $theSpec =$spec->fetch();
    
    $class =$theSpec['sub_class_id'];
    $mode =$theSpec['program_mode'];
    $level =$theSpec['level_id'];

    $dept=$conn->prepare("SELECT * from tbl_specialization where dept_id IN(select Department from tbl_staff where staff_id =:staff_id) and splz_id =:spec");
    $dept->bindParam(':staff_id',$id);
    $dept->bindParam(':spec',$class);
    $dept->execute();
    $theDept=$dept->fetch();
                                        
    $programType =$conn->prepare("select prg_type_full_name from tbl_program_type where prg_type_id =:prg_type_id");
    $programType->bindParam(':prg_type_id',$theDept['prg_type']);
    $programType->execute();
    $theType =$programType->fetch();
                        
    $theTypeName =$theType['prg_type_full_name'];
    $type  =$theDept['prg_type'];
    
    $prograMode =$conn->prepare("select prg_mode_full_name from tbl_program_mode where prg_mode_id =:prg_mode_id");
    $prograMode->bindParam(':prg_mode_id',$mode);
    $prograMode->execute();
    $theMode =$prograMode->fetch();
                                        
    $theModeName =$theMode['prg_mode_full_name'];    
                                        ?>
                        <div class="col-20">
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-sm-12 table-responsive">
                                        <div class="svg-container">
                                            <!--<a href ="schedules/download_time_table.php?class=<?php echo $_REQUEST['spec'];?>&levelId=<?php echo $_REQUEST['level'];?>&termId=<?php echo $_REQUEST['term'];?>&type=<?php echo $theDept['prg_type'];?>&mode=<?php echo $_REQUEST['mode'];?>"  target="_blank">-->
                                            <!--    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="red" class="bi bi-cloud-download" viewBox="0 0 16 16">-->
                                            <!--        <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>-->
                                            <!--        <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>-->
                                            <!--    </svg>-->
                                            <!--</a>-->
                                        </div>                                        
                                        <table class="table table-striped table-hover" id="my-table" >
                            				
                                    			<thead>
                                    				<tr> 
                                    					<th colspan="3"><?php echo $theDept['splz_full_name']." Level: ".$level." Term: ".$term. " Program-Type: ".$theTypeName." Program-Mode: ".$theModeName;?></th>
                                    				    <th>
                                    				        <input type="text" class="form-control" name="attendance-name"  id="attendance-name" placeholder="attendance name">
                                    				        <!--value="<?php echo date('l', strtotime(date('Y-m-d'))).date('Y-m-d');?>"-->
                                    				    </th>
                                    				</tr>
                                    				
                                    			</thead>									        
                            			
                            			<tr>
                                    		<th>Reg Number</th>
                                    		<th>Attendance Status</th>
                                    		<th colspan="2">Additional Info</th>
                                    	</tr>
                                    	<input type ="hidden" name="class" id="class" value="<?php echo $class['reg_no'];?>">
            							<input type ="hidden" name="mode" id="mode" value="<?php echo $mode;?>">
            							<input type ="hidden" name="type" id="type" value="<?php echo $type;?>">
            							<input type ="hidden" name="level" id="level" value="<?php echo $level;?>">
            						    <input type ="hidden" name="day" id="day" value="<?php echo $theday['day_id'];?>">
                                        <tbody>
                                            
            								    <?php 
            									  $regNumbers =$conn->prepare("select *from tbl_register_program_ug where splz_id =:class_id and level_id =:level_id and prg_mode_id =:mode_id and prg_type =:type_id and reg_active =:reg_status
            									  and reg_no IN(select reg_no from tbl_markby_module where splz_id =:spec and module_id =:module and enrolled =:enroll and status =:status)");
            									  
            									  $regNumbers->bindParam(':class_id', $class, PDO::PARAM_INT);
            									  $regNumbers->bindParam(':level_id', $level, PDO::PARAM_INT);
            									  $regNumbers->bindParam(':mode_id', $mode, PDO::PARAM_INT);
            									  $regNumbers->bindParam(':type_id', $type, PDO::PARAM_INT);
            									  $regNumbers->bindValue(':reg_status', 1, PDO::PARAM_INT);
            									  $regNumbers->bindParam(':spec', $class, PDO::PARAM_INT);
            									  $regNumbers->bindParam(':module', $module, PDO::PARAM_INT);
            									  $regNumbers->bindvalue(':enroll', 1, PDO::PARAM_INT);
            									  $regNumbers->bindValue(':status', 1, PDO::PARAM_INT);
            									  
            									  $regNumbers->execute();
            									  while($theRegNumber=$regNumbers->fetch()){
            									      
                									   //   check for existance
                									   $ifInattendanceToday =$conn->prepare("SELECT *from tbl_class_attendance where program_type = :prg_type and program_mode = :prg_mode and specialization = :specs 
                									   and level = :level and module_id = :module_id and student_reg_number = :registration and date = :date  and attendance_lecturer = :atttendance_teacher ");
                									   
                									  $ifInattendanceToday->bindParam(':prg_type', $type, PDO::PARAM_INT);
                									  $ifInattendanceToday->bindParam(':prg_mode', $mode, PDO::PARAM_INT);
                									  $ifInattendanceToday->bindParam(':specs', $class, PDO::PARAM_INT);
                									  $ifInattendanceToday->bindParam(':level', $level, PDO::PARAM_INT);
                									  $ifInattendanceToday->bindParam(':module_id', $module, PDO::PARAM_INT);
                									  $ifInattendanceToday->bindParam(':registration', $theRegNumber['reg_no'], PDO::PARAM_STR);
                									  $ifInattendanceToday->bindParam(':date', date('Y-m-d'), PDO::PARAM_STR);
                									  $ifInattendanceToday->bindParam(':atttendance_teacher', $id, PDO::PARAM_STR);
                									  $ifInattendanceToday->execute();
                									  
                									  if(($ifInattendanceToday->rowCount())>0){
                									      $itIsInAtrendance =$ifInattendanceToday->fetch();
                									  
                								           ?>
                								           <tr>
                								               <input type ="hidden" name="reg_numbs_copy[]" id="reg_numbs_copy" value="<?php echo $itIsInAtrendance['attendance_id'];?>">
                								               <td><?php echo $itIsInAtrendance['student_reg_number'];?></td>
                								           
                        									    <td>
                        									        <input type="checkbox" class="form-control" id="attended_copy" name="attended_copy[<?php echo $itIsInAtrendance['attendance_id'];?>]" checked>
                        									    </td>
                        									    <td colspan="2">
                        									        <input type="text" class="form-control" name="comment_copy[]" id="comment_copy" value="<?php echo $itIsInAtrendance['additional_info'];?>">
                        									    </td>
                    									  </tr>
                										    <?php
            									       }
            									       else{
               								           ?>
                								           <tr>
                								               <input type ="hidden" name="reg_numbs[]" id="reg_nums" value="<?php echo $theRegNumber['reg_no'];?>">
                								               <td><?php echo $theRegNumber['reg_no'];?></td>
                								           
                        									    <td>
                        									        <input type="checkbox" class="form-control" id="attended" name="attended[<?php echo $theRegNumber['reg_no'];?>]">
                        									    </td>
                        									    <td colspan="2">
                        									        <input type="text" class="form-control" name="comment[]" id="comment">
                        									    </td>
                    									  </tr>
                										    <?php
            									       }
            									     }
            									     ?> 
                                            </tbody>
                                          </table>
                                          
                                         </div>
                                    </div>
                                </div>
                            </div>
        <!--            </div>-->
        <!--        </div>-->
        <!--    </section>-->
        <!--</div>    -->